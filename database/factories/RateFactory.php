<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Rate;
use Illuminate\Database\Eloquent\Factories\Factory;

class RateFactory extends Factory
{
    protected $model = Rate::class;

    public function definition()
    {
        return [
            'country_id' => Country::factory(),
            'package_type' => $this->faker->randomElement(['Document', 'Parcel']),
            'weight' => $this->faker->randomFloat(2, 0.1, 100),
            'zone' => $this->faker->numberBetween(1, 8),
            'type' => $this->faker->randomElement(['personal', 'business']),
            'price' => $this->faker->randomFloat(2, 1, 1000),
        ];
    }
}
