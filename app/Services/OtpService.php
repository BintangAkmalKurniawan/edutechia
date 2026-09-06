<?php

namespace App\Services;

use App\Models\LoginOtp;
use App\Models\User;
use App\Notifications\LoginOtpNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OtpService
{
    public const PURPOSE = 'registration';

    public function issue(User $user, Request $request): LoginOtp
    {
        LoginOtp::query()
            ->where('user_id', $user->id)
            ->where('purpose', self::PURPOSE)
            ->whereNull('consumed_at')
            ->update(['consumed_at' => now()]);

        $code = (string) random_int(100000, 999999);

        $otp = LoginOtp::create([
            'user_id' => $user->id,
            'purpose' => self::PURPOSE,
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes((int) config('auth.otp_expiration', 10)),
            'requested_ip' => $request->ip(),
        ]);

        $user->notify(new LoginOtpNotification($code, $otp->expires_at));

        return $otp;
    }
}
