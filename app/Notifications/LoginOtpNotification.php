<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class LoginOtpNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly string $code,
        public readonly Carbon $expiresAt,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Kode verifikasi registrasi Edutechia')
            ->greeting('Halo, '.$notifiable->name.'!')
            ->line('Gunakan kode berikut untuk menyelesaikan registrasi akun Edutechia Anda:')
            ->line('**'.$this->code.'**')
            ->line('Kode ini berlaku sampai '.$this->expiresAt->timezone(config('app.timezone'))->format('H:i').' dan hanya dapat digunakan satu kali.')
            ->line('Jika Anda tidak melakukan registrasi ini, abaikan email ini.');
    }
}
