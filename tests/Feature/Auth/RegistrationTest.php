<?php

use App\Models\User;
use App\Notifications\LoginOtpNotification;
use Illuminate\Support\Facades\Notification;

test('registration screen can be rendered', function () {
    $this->get('/register')->assertOk();
});

test('new public registrations always create a student pending otp', function () {
    Notification::fake();

    $response = $this->post('/register', [
        'name' => 'Test Student',
        'email' => 'student@example.com',
        'institution_id' => 'S-100',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $user = User::where('email', 'student@example.com')->firstOrFail();
    expect($user->role)->toBe(User::ROLE_STUDENT);
    expect($user->email_verified_at)->toBeNull();
    $this->assertGuest();
    $response->assertRedirect(route('otp.challenge'));
    $this->assertDatabaseHas('login_otps', ['user_id' => $user->id, 'purpose' => 'registration']);
    Notification::assertSentTo($user, LoginOtpNotification::class);
});

test('registration otp verifies email and authenticates the student', function () {
    Notification::fake();
    $this->post('/register', [
        'name' => 'Test Student',
        'email' => 'student@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);
    $user = User::where('email', 'student@example.com')->firstOrFail();

    $code = null;
    Notification::assertSentTo($user, LoginOtpNotification::class, function ($notification) use (&$code) {
        $code = $notification->code;

        return true;
    });

    $this->post(route('otp.verify'), ['code' => $code])->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
    expect($user->fresh()->email_verified_at)->not->toBeNull();
});
