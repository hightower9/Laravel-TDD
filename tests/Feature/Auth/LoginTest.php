<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_email_and_password(): void
    {
        $user = $this->createUser();

        $res = $this->postJson(route('user.login'), [
            'email'    => $user->email,
            'password' => 'password',
        ])
        ->assertOk()->json('data');

        $this->assertArrayHasKey('token', $res);
    }

    public function test_if_email_user_is_not_present_then_return_error(): void
    {
        $this->postJson(route('user.login'), [
            'email'    => 'abc@abc.com',
            'password' => 'password',
        ])
        ->assertUnauthorized();
    }

    public function test_if_user_password_is_wrong_then_return_error(): void
    {
        $user = $this->createUser();

        $this->postJson(route('user.login'), [
            'email'    => $user->email,
            'password' => 'password@1234',
        ])
        ->assertUnauthorized();
    }
}
