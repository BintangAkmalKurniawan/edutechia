<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginOtp;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginOtpController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        $user = $this->pendingUser($request);

        if (! $user) {
            return redirect()->route('login');
        }

        return view('auth.otp', [
            'maskedEmail' => $this->maskEmail($user->email),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $user = $this->pendingUser($request);

        if (! $user || ! $user->isActive()) {
            return redirect()->route('login')->withErrors(['email' => 'Sesi verifikasi telah berakhir. Silakan masuk kembali.']);
        }

        $error = DB::transaction(function () use ($validated, $user): ?string {
            $otp = LoginOtp::query()
                ->where('user_id', $user->id)
                ->where('purpose', OtpService::PURPOSE)
                ->whereNull('consumed_at')
                ->latest('id')
                ->lockForUpdate()
                ->first();

            if (! $otp || $otp->expires_at->isPast()) {
                return 'Kode OTP sudah kedaluwarsa. Minta kode baru.';
            }

            if ($otp->attempts >= 5) {
                $otp->update(['consumed_at' => now()]);

                return 'Terlalu banyak percobaan. Minta kode OTP baru.';
            }

            if (! Hash::check($validated['code'], $otp->code_hash)) {
                $otp->increment('attempts');

                return 'Kode OTP tidak sesuai.';
            }

            $otp->update(['consumed_at' => now()]);
            $user->forceFill([
                'email_verified_at' => $user->email_verified_at ?? now(),
                'last_login_at' => now(),
            ])->save();

            return null;
        });

        if ($error) {
            throw ValidationException::withMessages(['code' => $error]);
        }

        $remember = (bool) $request->session()->pull('otp_remember', false);
        $request->session()->forget('otp_user_id');
        Auth::login($user, $remember);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false))
            ->with('success', 'Registrasi berhasil diverifikasi. Selamat datang!');
    }

    public function resend(Request $request, OtpService $otpService): RedirectResponse
    {
        $user = $this->pendingUser($request);

        if (! $user) {
            return redirect()->route('login');
        }

        try {
            $otpService->issue($user, $request);
        } catch (\Throwable $exception) {
            report($exception);

            return back()->withErrors(['code' => 'Kode OTP belum dapat dikirim. Periksa konfigurasi SMTP atau coba kembali.']);
        }

        return back()->with('status', 'Kode OTP baru telah dikirim.');
    }

    public function cancel(Request $request): RedirectResponse
    {
        $request->session()->forget(['otp_user_id', 'otp_remember']);

        return redirect()->route('login');
    }

    private function pendingUser(Request $request): ?User
    {
        $id = $request->session()->get('otp_user_id');

        return $id ? User::find($id) : null;
    }

    private function maskEmail(string $email): string
    {
        [$name, $domain] = explode('@', $email, 2);
        $visible = mb_substr($name, 0, min(2, mb_strlen($name)));

        return $visible.str_repeat('•', max(3, mb_strlen($name) - 2)).'@'.$domain;
    }
}
