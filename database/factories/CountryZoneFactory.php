<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\CountryZone;
use Illuminate\Database\Eloquent\Factories\Factory;

class CountryZoneFactory extends Factory
{
    protected $model = CountryZone::class;

    public function definition()
    {
        return [
            'country_id' => Country::factory(),
            'name' => $this->faker->country,
            'zone' => $this->faker->numberBetween(1, 8),
        ];
    }
}
