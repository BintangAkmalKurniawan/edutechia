<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request, OtpService $otpService): RedirectResponse
    {
        $user = $request->authenticatedUser();

        if ($user->email_verified_at) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();
            $request->session()->forget(['otp_user_id', 'otp_remember']);
            $user->forceFill(['last_login_at' => now()])->save();

            return redirect()->intended(route('dashboard', absolute: false));
        }

        $request->session()->regenerate();
        $request->session()->put([
            'otp_user_id' => $user->id,
            'otp_remember' => $request->boolean('remember'),
        ]);

        try {
            $otpService->issue($user, $request);
        } catch (\Throwable $exception) {
            report($exception);

            throw ValidationException::withMessages([
                'email' => 'Kode OTP belum dapat dikirim. Periksa konfigurasi SMTP atau coba kembali.',
            ]);
        }

        return redirect()->route('otp.challenge')
            ->with('status', 'Registrasi Anda belum terverifikasi. Masukkan kode OTP baru yang telah kami kirim.');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
