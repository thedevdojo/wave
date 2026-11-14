<?php

use App\Models\User;
use Devdojo\Auth\Traits\AuthenticatesUsers;
use Illuminate\Auth\Events\Attempting;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\Livewire;
use Livewire\Volt\Volt;

beforeEach(function () {
    config([
        'devdojo.auth.settings.enable_2fa' => false,
        'devdojo.auth.settings.login_show_password_same_screen' => true,
        'wave.auth.login_max_attempts' => 5,
        'wave.auth.login_ip_max_attempts' => 50,
        'wave.auth.login_decay_seconds' => 60,
    ]);
    $this->user = User::factory()->create(['password' => bcrypt('login-password-123')]);
});

function throttledLoginForm(string $email)
{
    return Volt::test('auth.login')->set('email', $email)->set('password', 'wrong-password');
}

it('blocks even the correct password after five failures on the normal login page', function () {
    Event::fake([Lockout::class]);
    $form = throttledLoginForm($this->user->email);
    foreach (range(1, 5) as $i) {
        $form->call('authenticate')->assertHasErrors('password');
    }
    $form->set('password', 'login-password-123')->call('authenticate')->assertHasErrors('password');
    $this->assertGuest();
    Event::assertDispatched(Lockout::class);
    $this->travel(61)->seconds();
    $form->call('authenticate')->assertHasNoErrors();
    $this->assertAuthenticatedAs($this->user);
});

it('limits the email-first flow at the password submission step', function () {
    config(['devdojo.auth.settings.login_show_password_same_screen' => false]);
    $form = Volt::test('auth.login')->set('email', $this->user->email)->call('authenticate')->assertHasNoErrors();
    foreach (range(1, 5) as $i) {
        $form->set('password', 'wrong-password')->call('authenticate')->assertHasErrors('password');
    }
    $form->set('password', 'login-password-123')->call('authenticate')->assertHasErrors('password');
    $this->assertGuest();
});

it('normalizes email case so different casing cannot reset the failure budget', function () {
    for ($i = 0; $i < 5; $i++) {
        throttledLoginForm($i % 2 ? strtoupper($this->user->email) : strtolower($this->user->email))
            ->call('authenticate')->assertHasErrors('password');
    }
    throttledLoginForm($this->user->email)->set('password', 'login-password-123')->call('authenticate')->assertHasErrors('password');
    $this->assertGuest();
});

it('clears the account failure budget after a successful login', function () {
    foreach (range(1, 4) as $i) {
        throttledLoginForm($this->user->email)->call('authenticate')->assertHasErrors('password');
    }
    throttledLoginForm($this->user->email)->set('password', 'login-password-123')->call('authenticate')->assertHasNoErrors();
    $this->assertAuthenticatedAs($this->user);
    Auth::logout();
    foreach (range(1, 4) as $i) {
        throttledLoginForm($this->user->email)->call('authenticate')->assertHasErrors('password');
    }
    throttledLoginForm($this->user->email)->set('password', 'login-password-123')->call('authenticate')->assertHasNoErrors();
    $this->assertAuthenticatedAs($this->user);
});

it('limits spraying across different and nonexistent accounts from the same IP', function () {
    config(['wave.auth.login_ip_max_attempts' => 3]);
    foreach (range(1, 3) as $i) {
        throttledLoginForm('missing'.$i.'@example.test')->call('authenticate')->assertHasErrors('password');
    }
    throttledLoginForm($this->user->email)->set('password', 'login-password-123')->call('authenticate')->assertHasErrors('password');
    $this->assertGuest();
});

it('keeps different IP failure budgets separate', function () {
    request()->server->set('REMOTE_ADDR', '203.0.113.10');
    foreach (range(1, 5) as $i) {
        event(new Failed('web', null, ['email' => $this->user->email, 'password' => 'wrong']));
    }
    request()->server->set('REMOTE_ADDR', '203.0.113.11');
    event(new Attempting('web', ['email' => $this->user->email, 'password' => 'right'], false));
    request()->server->set('REMOTE_ADDR', '203.0.113.10');
    expect(fn () => event(new Attempting('web', ['email' => $this->user->email, 'password' => 'right'], false)))
        ->toThrow(ValidationException::class);
});

it('does not let a successful account reset the IP-wide spraying budget', function () {
    config(['wave.auth.login_ip_max_attempts' => 2]);
    event(new Failed('web', null, ['email' => 'missing@example.test', 'password' => 'wrong']));
    event(new Login('web', $this->user, false));
    event(new Failed('web', null, ['email' => 'another@example.test', 'password' => 'wrong']));
    expect(fn () => event(new Attempting('web', ['email' => $this->user->email, 'password' => 'right'], false)))
        ->toThrow(ValidationException::class);
});

it('also guards direct SessionGuard password attempts', function () {
    foreach (range(1, 5) as $i) {
        expect(Auth::attempt(['email' => $this->user->email, 'password' => 'wrong']))->toBeFalse();
    }
    expect(fn () => Auth::attempt(['email' => $this->user->email, 'password' => 'login-password-123']))
        ->toThrow(ValidationException::class);
    $this->assertGuest();
});

it('does not count failures for other guards or credentials without a password', function () {
    foreach (range(1, 10) as $i) {
        event(new Failed('api', null, ['email' => $this->user->email, 'password' => 'wrong']));
        event(new Failed('web', null, ['email' => $this->user->email]));
    }
    throttledLoginForm($this->user->email)->set('password', 'login-password-123')->call('authenticate')->assertHasNoErrors();
    $this->assertAuthenticatedAs($this->user);
});

it('still starts the two-factor challenge after valid credentials within the limit', function () {
    config(['devdojo.auth.settings.enable_2fa' => true]);
    $this->user->forceFill(['two_factor_confirmed_at' => now()])->save();
    foreach (range(1, 4) as $i) {
        throttledLoginForm($this->user->email)->call('authenticate')->assertHasErrors('password');
    }
    throttledLoginForm($this->user->email)->set('password', 'login-password-123')->call('authenticate')
        ->assertHasNoErrors()->assertRedirect(route('auth.two-factor-challenge'));
    $this->assertGuest();
    expect(session('login.id'))->toBe($this->user->id);
});

class AliasedPasswordLogin extends Component
{
    use AuthenticatesUsers {
        authenticate as public passwordAlias;
    }

    public function render()
    {
        return '<div></div>';
    }
}

it('cannot reset the shared limit by calling a public trait alias on another component', function () {
    foreach (range(1, 5) as $i) {
        Livewire::test(AliasedPasswordLogin::class)->set('email', $this->user->email)->set('password', 'wrong-password')
            ->call('passwordAlias')->assertHasErrors('password');
    }
    throttledLoginForm($this->user->email)->set('password', 'login-password-123')->call('authenticate')->assertHasErrors('password');
    $this->assertGuest();
});
