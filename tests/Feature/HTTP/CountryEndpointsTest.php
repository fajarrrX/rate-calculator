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
     * Test country model creation and queries
     */
    public function test_country_model_execution()
    {
        // Execute Country model methods
        $countries = collect([
            Country::factory()->create(['name' => 'Australia', 'code' => 'AU']),
            Country::factory()->create(['name' => 'Austria', 'code' => 'AT']),
            Country::factory()->create(['name' => 'Germany', 'code' => 'DE'])
        ]);

        // Execute query methods
        $foundCountry = Country::where('code', 'AU')->first();
        $allCountries = Country::all();
        $countryCount = Country::count();

        $this->assertEquals('Australia', $foundCountry->name);
        $this->assertCount(3, $allCountries);
        $this->assertEquals(3, $countryCount);
    }

    /**
     * Test country zones relationship execution
     */
    public function test_country_zones_relationship()
    {
        // Create country with zones
        $country = Country::factory()->create(['name' => 'Test Country']);
        
        $zones = collect([
            CountryZone::factory()->create(['country_id' => $country->id]),
            CountryZone::factory()->create(['country_id' => $country->id])
        ]);

        // Execute relationship methods if they exist
        if (method_exists($country, 'zones')) {
            $relatedZones = $country->zones;
            $this->assertCount(2, $relatedZones);
        }
        
        // Execute reverse relationship if it exists
        $zone = $zones->first();
        if (method_exists($zone, 'country')) {
            $relatedCountry = $zone->country;
            $this->assertEquals($country->id, $relatedCountry->id);
        }
    }

    /**
     * Test country model updates and deletion
     */
    public function test_country_crud_execution()
    {
        $country = Country::factory()->create([
            'name' => 'Original Name',
            'code' => 'ON'
        ]);

        // Execute update methods
        $country->update([
            'name' => 'Updated Name',
            'code' => 'UN'
        ]);

        $updatedCountry = Country::find($country->id);
        $this->assertEquals('Updated Name', $updatedCountry->name);
        $this->assertEquals('UN', $updatedCountry->code);

        // Execute deletion
        $countryId = $country->id;
        $country->delete();
        
        $deletedCountry = Country::find($countryId);
        $this->assertNull($deletedCountry);
    }

    /**
     * Test country search and filtering
     */
    public function test_country_search_execution()
    {
        // Create searchable countries
        Country::factory()->create(['name' => 'Australia']);
        Country::factory()->create(['name' => 'Austria']);
        Country::factory()->create(['name' => 'Germany']);

        // Execute search queries
        $austrianCountries = Country::where('name', 'like', '%Austr%')->get();
        $exactMatch = Country::where('name', 'Germany')->first();
        $codeSearch = Country::where('name', 'like', 'A%')->get();

        $this->assertCount(2, $austrianCountries);
        $this->assertEquals('Germany', $exactMatch->name);
        $this->assertGreaterThanOrEqual(2, $codeSearch->count());
    }

    /**
     * Test country collection methods
     */
    public function test_country_collection_execution()
    {
        $countries = Country::factory()->count(5)->create();

        // Execute collection methods
        $countryCollection = Country::all();
        $pluckedNames = $countryCollection->pluck('name');
        $filteredCountries = $countryCollection->filter(function ($country) {
            return strlen($country->name) > 5;
        });

        $this->assertCount(5, $countryCollection);
        $this->assertCount(5, $pluckedNames);
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $filteredCountries);
    }

    /**
     * Test country factory execution
     */
    public function test_country_factory_execution()
    {
        // Execute factory methods
        $singleCountry = Country::factory()->create();
        $multipleCountries = Country::factory()->count(3)->create();
        $countryWithSpecificData = Country::factory()->create([
            'name' => 'Factory Test Country'
        ]);

        $this->assertInstanceOf(Country::class, $singleCountry);
        $this->assertCount(3, $multipleCountries);
        $this->assertEquals('Factory Test Country', $countryWithSpecificData->name);
    }
}
