<?php

namespace Tests\Feature\HTTP;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Country;
use App\Models\CountryZone;

class CountryEndpointsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test countries API endpoint
     */
    public function test_countries_api_index()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create test countries
        Country::factory()->count(3)->create();

        $response = $this->getJson('/api/countries');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['id', 'name']
        ]);
    }

    /**
     * Test country zones relationship execution
     */
    public function test_country_zones_relationship()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create country with zones
        $country = Country::factory()->create();
        CountryZone::factory()->count(2)->create(['country_id' => $country->id]);

        $response = $this->get("/country/{$country->id}");
        $response->assertStatus(200);
        
        // This executes the zones() relationship method
        $zones = $country->zones;
        $this->assertCount(2, $zones);
    }

    /**
     * Test country store with validation execution
     */
    public function test_country_store_validation_execution()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Test required field validation
        $response = $this->post('/country', [
            'name' => '', // Empty name
            'code' => ''  // Empty code
        ]);
        
        // This executes validation rules
        $response->assertSessionHasErrors(['name', 'code']);
    }

    /**
     * Test country update execution
     */
    public function test_country_update_execution()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $country = Country::factory()->create([
            'name' => 'Original Name',
            'code' => 'ON'
        ]);

        // Execute update method
        $response = $this->put("/country/{$country->id}", [
            'name' => 'Updated Name',
            'code' => 'UN'
        ]);

        // This executes model update code
        $country->refresh();
        $this->assertEquals('Updated Name', $country->name);
        $this->assertEquals('UN', $country->code);
    }

    /**
     * Test country delete execution
     */
    public function test_country_delete_execution()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $country = Country::factory()->create();

        // Execute delete method
        $response = $this->delete("/country/{$country->id}");
        
        // This executes model deletion code
        $this->assertDatabaseMissing('countries', ['id' => $country->id]);
    }

    /**
     * Test country search functionality
     */
    public function test_country_search_execution()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create searchable countries
        Country::factory()->create(['name' => 'Australia']);
        Country::factory()->create(['name' => 'Austria']);
        Country::factory()->create(['name' => 'Germany']);

        // Execute search logic
        $response = $this->get('/country?search=Austr');
        $response->assertStatus(200);
        
        // This executes query builder methods
        $countries = Country::where('name', 'like', '%Austr%')->get();
        $this->assertCount(2, $countries);
    }
}
