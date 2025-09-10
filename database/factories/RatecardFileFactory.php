<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\RatecardFile;
use Illuminate\Database\Eloquent\Factories\Factory;

class RatecardFileFactory extends Factory
{
    protected $model = RatecardFile::class;

    public function definition()
    {
        return [
            'country_id' => Country::factory(),
            'type' => $this->faker->randomElement(['personal', 'business']),
            'name' => $this->faker->word . '.xlsx',
            'path' => 'ratecards/' . $this->faker->word . '/' . $this->faker->word . '.xlsx',
        ];
    }
}
