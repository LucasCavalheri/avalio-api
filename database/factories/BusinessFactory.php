<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Business>
 */
class BusinessFactory extends Factory
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
            'name' => fake()->company(),
            'description' => fake()->sentence(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->email(),
            'state' => fake()->state(),
            'city' => fake()->city(),
            'neighborhood' => fake()->city(),
            'street' => fake()->streetName(),
            'number' => fake()->buildingNumber(),
            'zip_code' => fake()->postcode(),
            'complement' => fake()->secondaryAddress(),
            'reference' => fake()->sentence(),
            'banner_image' => fake()->imageUrl(),
            'cover_image' => fake()->imageUrl(),
        ];
    }
}
