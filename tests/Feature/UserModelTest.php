<?php

namespace Tests\Feature;

use App\Models\Users\User;
use Database\Factories\UserFactory;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    public function test_nested_user_model_resolves_through_authentication_and_factories(): void
    {
        $this->assertSame(User::class, config('auth.providers.users.model'));
        $this->assertInstanceOf(UserFactory::class, User::factory());
        $this->assertInstanceOf(User::class, UserFactory::new()->make());
    }
}
