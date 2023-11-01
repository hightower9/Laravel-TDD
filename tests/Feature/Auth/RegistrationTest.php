<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_user_can_register(): void
    {
        $this->postJson(route('user.register'), [
            'name'                  => 'John Doe',
            'email'                 => 'john@example.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
        ])
        ->assertCreated()->json('data');

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe', 
            'email' => 'john@example.com',
        ]);
    }
}
