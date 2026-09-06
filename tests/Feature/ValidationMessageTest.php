<?php

use Illuminate\Support\Facades\Validator;

test('validation errors use readable Indonesian messages and field names', function () {
    $validator = Validator::make([
        'description' => 'Terlalu singkat',
        'email' => 'email-tidak-valid',
        'duration_minutes' => 0,
        'questions' => [
            ['options' => ['Satu']],
        ],
    ], [
        'description' => ['string', 'min:20'],
        'email' => ['email'],
        'duration_minutes' => ['integer', 'min:1'],
        'questions.*.options' => ['array', 'min:2'],
    ]);

    expect($validator->errors()->first('description'))
        ->toBe('Deskripsi kelas minimal terdiri dari 20 karakter.')
        ->and($validator->errors()->first('email'))
        ->toBe('Alamat email harus berupa alamat email yang valid.')
        ->and($validator->errors()->first('duration_minutes'))
        ->toBe('Durasi minimal bernilai 1.')
        ->and($validator->errors()->first('questions.0.options'))
        ->toBe('Daftar pilihan jawaban minimal memiliki 2 item.');

    foreach ($validator->errors()->all() as $message) {
        expect($message)->not->toMatch('/^(validation|auth|passwords)\./');
    }
});

test('required and password confirmation errors are clear for registration', function () {
    $response = $this->from(route('register'))->post(route('register'), [
        'name' => '',
        'email' => 'siswa@example.com',
        'password' => 'rahasia123',
        'password_confirmation' => 'berbeda',
    ]);

    $response->assertRedirect(route('register'))
        ->assertSessionHasErrors([
            'name' => 'Nama lengkap wajib diisi.',
            'password' => 'Kata sandi dan konfirmasinya tidak cocok.',
        ]);
});

test('authentication errors never expose translation keys', function () {
    $response = $this->from(route('login'))->post(route('login'), [
        'email' => 'tidak.ada@example.com',
        'password' => 'password',
    ]);

    $response->assertRedirect(route('login'))
        ->assertSessionHasErrors([
            'email' => 'Email atau kata sandi tidak sesuai.',
        ]);
});
