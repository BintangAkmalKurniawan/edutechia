<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('edutechia:create-admin {--name=} {--email=}', function () {
    $name = $this->option('name') ?: $this->ask('Nama administrator');
    $email = mb_strtolower($this->option('email') ?: $this->ask('Email administrator'));
    $password = $this->secret('Kata sandi (minimal 8 karakter)');
    $confirmation = $this->secret('Ulangi kata sandi');

    if ($password !== $confirmation) {
        $this->error('Konfirmasi kata sandi tidak sama.');

        return 1;
    }

    $validator = Validator::make(compact('name', 'email', 'password'), [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', Password::defaults()],
    ]);

    if ($validator->fails()) {
        foreach ($validator->errors()->all() as $error) {
            $this->error($error);
        }

        return 1;
    }

    User::create([
        'name' => $name,
        'email' => $email,
        'role' => User::ROLE_ADMIN,
        'status' => 'active',
        'email_verified_at' => now(),
        'password' => Hash::make($password),
    ]);

    $this->info('Administrator berhasil dibuat dan dapat langsung masuk.');

    return 0;
})->purpose('Membuat akun administrator Edutechia secara aman');
