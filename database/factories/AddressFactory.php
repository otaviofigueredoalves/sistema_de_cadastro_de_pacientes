<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'street' => fake()->streetName(),
            'zip_code' => fake()->numerify('########'),
            'neighborhood' => fake()->citySuffix(),
            'city' => fake()->city(),
            'state' => strtoupper(fake()->lexify('??')), // Simple 2 char state
        ];
    }
}
