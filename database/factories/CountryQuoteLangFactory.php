<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\CountryQuoteLang;
use Illuminate\Database\Eloquent\Factories\Factory;

class CountryQuoteLangFactory extends Factory
{
    protected $model = CountryQuoteLang::class;

    public function definition()
    {
        return [
            'country_id' => Country::factory(),
            'lang' => $this->faker->languageCode,
            'receiver_company_label' => $this->faker->word,
            'receiver_name_label' => $this->faker->word,
            'receiver_address_label' => $this->faker->word,
            'receiver_phone_label' => $this->faker->word,
        ];
    }
}
