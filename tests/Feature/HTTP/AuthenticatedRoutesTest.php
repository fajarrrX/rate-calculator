<?php

namespace Tests\Feature\HTTP;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class AuthenticatedRoutesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test home page with authentication
     */
    public function test_home_page_with_auth()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/home');
        $response->assertStatus(200);
    }

    /**
     * Test countries index with authentication
     */
    public function test_countries_index_with_auth()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/country');
        $response->assertStatus(200);
    }

    /**
     * Test countries create with authentication
     */
    public function test_countries_create_with_auth()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/country/create');
        $response->assertStatus(200);
    }

    /**
     * Test country store with validation
     */
    public function test_country_store_validation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Test with empty data (executes validation code)
        $response = $this->post('/country', []);
        $response->assertSessionHasErrors();
    }

    /**
     * Test country store with valid data
     */
    public function test_country_store_success()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = [
            'name' => 'Test Country',
            'code' => 'TC'
        ];

        $response = $this->post('/country', $data);
        $response->assertRedirect();
        
        $this->assertDatabaseHas('countries', $data);
    }
}
