<?php

namespace Database\Factories\Service;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExternalService>
 */
class ExternalServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name'    => 'Google',
            'token'   => [
                'access_token' => encrypt(fake()->uuid()),
                'refresh_token' => encrypt(fake()->uuid()),
            ],
        ];
    }
}
