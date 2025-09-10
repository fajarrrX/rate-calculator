<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class CountryFactory extends Factory
{
    protected $model = Country::class;

    public function definition()
    {
        return [
            'name' => $this->faker->country,
            'code' => $this->faker->unique()->countryCode,
            'currency_code' => $this->faker->currencyCode,
            'decimal_places' => $this->faker->numberBetween(0, 4),
            'symbol_after_personal_price' => $this->faker->randomElement(['$', '€', '£', '¥']),
            'symbol_after_business_price' => $this->faker->randomElement(['$', '€', '£', '¥']),
            'hide_package_opt_en' => $this->faker->boolean,
            'hide_package_opt_local' => $this->faker->boolean,
            'hide_step_1' => $this->faker->boolean,
            'share_country_id' => null,
        ];
    }
}
