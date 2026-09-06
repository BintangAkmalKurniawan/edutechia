<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request, OtpService $otpService): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'institution_id' => ['nullable', 'string', 'max:80'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => User::ROLE_STUDENT,
            'institution_id' => $request->institution_id,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        $request->session()->put([
            'otp_user_id' => $user->id,
            'otp_remember' => false,
        ]);

        try {
            $otpService->issue($user, $request);
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()->route('otp.challenge')
                ->withErrors(['code' => 'Akun dibuat, tetapi OTP belum dapat dikirim. Periksa konfigurasi SMTP lalu gunakan tombol kirim ulang.']);
        }

        return redirect()->route('otp.challenge')
            ->with('status', 'Akun siswa berhasil dibuat. Masukkan OTP yang kami kirimkan.');
    }
}
