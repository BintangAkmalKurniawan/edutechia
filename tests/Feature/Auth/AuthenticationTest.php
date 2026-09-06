<?php

use App\Models\LoginOtp;
use App\Models\User;
use App\Notifications\LoginOtpNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

test('login screen can be rendered', function () {
    $this->get('/login')->assertOk();
});

test('verified users authenticate without an otp', function () {
    Notification::fake();
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route('dashboard', absolute: false));
    $this->assertDatabaseMissing('login_otps', ['user_id' => $user->id]);
    Notification::assertNothingSent();
    expect($user->fresh()->last_login_at)->not->toBeNull();
});

test('login before registration verification sends a new registration otp', function () {
    Notification::fake();
    $user = User::factory()->unverified()->create();
    $oldOtp = LoginOtp::create([
        'user_id' => $user->id,
        'purpose' => 'registration',
        'code_hash' => Hash::make('123456'),
        'expires_at' => now()->addMinutes(10),
    ]);

    $response = $this->post('/login', ['email' => $user->email, 'password' => 'password']);

    $this->assertGuest();
    $response->assertRedirect(route('otp.challenge'));
    expect($oldOtp->refresh()->consumed_at)->not->toBeNull();

    $newOtp = LoginOtp::where('user_id', $user->id)->latest('id')->firstOrFail();
    expect($newOtp->id)->not->toBe($oldOtp->id)
        ->and($newOtp->purpose)->toBe('registration')
        ->and($newOtp->consumed_at)->toBeNull();
    Notification::assertSentTo($user, LoginOtpNotification::class);
});

test('users can finish registration verification from the login flow', function () {
    Notification::fake();
    $user = User::factory()->unverified()->create();

    $this->post('/login', ['email' => $user->email, 'password' => 'password']);

    $code = null;
    Notification::assertSentTo($user, LoginOtpNotification::class, function ($notification) use (&$code) {
        $code = $notification->code;

        return true;
    });

    $response = $this->post(route('otp.verify'), ['code' => $code]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route('dashboard', absolute: false));
    expect($user->fresh()->email_verified_at)->not->toBeNull();
    expect($user->fresh()->last_login_at)->not->toBeNull();
    expect(LoginOtp::where('user_id', $user->id)->latest('id')->first()->consumed_at)->not->toBeNull();
});

test('invalid registration otp attempts are persisted and do not authenticate', function () {
    Notification::fake();
    $user = User::factory()->unverified()->create();
    $this->post('/login', ['email' => $user->email, 'password' => 'password']);

    $this->post(route('otp.verify'), ['code' => '000000'])->assertSessionHasErrors('code');

    $this->assertGuest();
    expect(LoginOtp::where('user_id', $user->id)->latest('id')->first()->attempts)->toBe(1);
});

test('users cannot authenticate with invalid password', function () {
    Notification::fake();
    $user = User::factory()->create();

    $this->post('/login', ['email' => $user->email, 'password' => 'wrong-password'])
        ->assertSessionHasErrors('email');

    $this->assertGuest();
    Notification::assertNothingSent();
});

test('inactive accounts are denied before otp delivery', function () {
    Notification::fake();
    $user = User::factory()->create(['status' => 'inactive']);

    $this->post('/login', ['email' => $user->email, 'password' => 'password'])
        ->assertSessionHasErrors('email');

    $this->assertGuest();
    Notification::assertNothingSent();
});

test('users can logout', function () {
    $user = User::factory()->create();
    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
