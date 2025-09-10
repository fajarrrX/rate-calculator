<?php

namespace Tests\Feature\Integration;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Country;
use App\Models\Rate;
use App\Models\User;

class ModelIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Country model CRUD operations
     */
    public function test_country_model_operations()
    {
        // Create a country
        $country = new Country();
        $country->name = 'Test Country';
        $country->code = 'TC';
        $country->save();

        $this->assertDatabaseHas('countries', [
            'name' => 'Test Country',
            'code' => 'TC'
        ]);

        // Test fillable attributes
        $fillable = $country->getFillable();
        $this->assertContains('name', $fillable);
        $this->assertContains('code', $fillable);

        // Test model methods if they exist
        if (method_exists($country, 'validFields')) {
            $validFields = $country->validFields();
            $this->assertIsArray($validFields);
        }
    }

    /**
     * Test Rate model operations
     */
    public function test_rate_model_operations()
    {
        // Create a country first
        $country = new Country();
        $country->name = 'Test Country';
        $country->code = 'TC';
        $country->save();

        // Create a rate
        $rate = new Rate();
        $rate->country_id = $country->id;
        $rate->zone = 'Zone 1';
        $rate->weight = '1kg';
        $rate->document_price = 100.00;
        $rate->parcel_price = 150.00;
        $rate->save();

        $this->assertDatabaseHas('rates', [
            'country_id' => $country->id,
            'zone' => 'Zone 1'
        ]);

        // Test fillable attributes
        $fillable = $rate->getFillable();
        $this->assertContains('country_id', $fillable);
    }

    /**
     * Test User model operations
     */
    public function test_user_model_operations()
    {
        $user = new User();
        $user->name = 'Test User';
        $user->email = 'test@example.com';
        $user->password = bcrypt('password');
        $user->save();

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com'
        ]);

        // Test hidden attributes
        $hidden = $user->getHidden();
        $this->assertContains('password', $hidden);

        // Test fillable attributes
        $fillable = $user->getFillable();
        $this->assertContains('name', $fillable);
        $this->assertContains('email', $fillable);
    }
}
