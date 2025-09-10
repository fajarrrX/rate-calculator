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
        // Create a country using factory (includes required currency_code)
        $country = Country::factory()->create([
            'name' => 'Test Country',
            'code' => 'TC'
        ]);

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
        // Create a country first using factory
        $country = Country::factory()->create([
            'name' => 'Test Country',
            'code' => 'TC'
        ]);

        // Create a rate using factory (correct schema)
        $rate = Rate::factory()->create([
            'country_id' => $country->id
        ]);
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
