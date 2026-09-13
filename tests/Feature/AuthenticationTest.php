<?php

namespace Tests\Feature;

use App\Models\Users\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_signup_requires_at_least_four_password_characters(): void
    {
        $data = ['name' => 'Writer', 'email' => 'short@example.com'];
        $this->post('/signup', [...$data, 'password' => 'abc', 'password_confirmation' => 'abc'])
            ->assertSessionHasErrors('password');
        $this->assertGuest();
        $this->post('/signup', [...$data, 'password' => 'abcd', 'password_confirmation' => 'abcd'])
            ->assertRedirect('/notes');
        $this->assertAuthenticated();
    }

    public function test_signup_cannot_assign_admin_role(): void
    {
        $this->post('/signup', ['name' => 'Writer', 'email' => 'writer@example.com', 'password' => 'long-password', 'password_confirmation' => 'long-password', 'role' => 'admin'])
            ->assertRedirect('/notes');
        $user = User::where('email', 'writer@example.com')->firstOrFail();
        $this->assertSame('user', $user->role);
        $this->assertAuthenticatedAs($user);
        $this->get('/admin')->assertForbidden();
    }

    public function test_admin_login_and_logout(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->post('/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect('/');
        $this->get('/admin')->assertOk();
        $this->post('/logout')->assertRedirect('/');
        $this->assertGuest();
        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_invalid_credentials_and_guest_pages(): void
    {
        $this->get('/login')->assertOk();
        $this->get('/signup')->assertOk()->assertDontSee('name="role"', false);
        $this->post('/login', ['email' => 'missing@example.com', 'password' => 'incorrect'])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_password_reset_link_updates_password(): void
    {
        Notification::fake();
        $user = User::factory()->create();

        $this->get('/login')->assertSee('Remember me')->assertSee('Forgot password?')->assertSee('Show Password');
        $this->get('/forgot-password')->assertOk();
        $this->post('/forgot-password', ['email' => $user->email])->assertSessionHas('status');

        $token = null;
        Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use (&$token) {
            $token = $notification->token;
            return true;
        });

        $this->get(route('password.reset', ['token' => $token, 'email' => $user->email]))->assertOk();
        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertRedirect('/login');

        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    }
}
