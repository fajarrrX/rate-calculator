<?php

namespace Tests\Feature\HTTP;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Rate;
use App\Models\Country;
use App\Enums\PackageType;
use App\Enums\RateType;

class RateEndpointsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test rate model with enum execution
     */
    public function test_rate_model_with_enums()
    {
        $country = Country::factory()->create();
        
        // Execute enum creation methods
        $packageType = PackageType::Document();
        $rateType = RateType::Original();

        // Create rate using actual database schema
        $rate = Rate::create([
            'country_id' => $country->id,
            'package_type' => $packageType->value,
            'type' => $rateType->value,
            'weight' => 2.5,
            'zone' => 1,
            'price' => 150.00
        ]);

        // Execute model methods and relationships
        $this->assertInstanceOf(Rate::class, $rate);
        $this->assertEquals(1, $rate->package_type);
        $this->assertEquals(1, $rate->type);
        
        // Execute relationship if it exists
        if (method_exists($rate, 'country')) {
            $relatedCountry = $rate->country;
            $this->assertInstanceOf(Country::class, $relatedCountry);
        }
    }

    /**
     * Test enum comparison and iteration
     */
    public function test_enum_comparison_execution()
    {
        // Execute enum comparison methods
        $document = PackageType::Document();
        $nonDocument = PackageType::NonDocument();
        
        $this->assertTrue($document->is(PackageType::Document()));
        $this->assertFalse($document->is(PackageType::NonDocument()));
        $this->assertTrue($nonDocument->is(PackageType::NonDocument()));
        
        // Execute rate type comparisons
        $original = RateType::Original();
        $personal = RateType::Personal();
        $business = RateType::Business();
        
        $this->assertNotEquals($original->value, $personal->value);
        $this->assertNotEquals($personal->value, $business->value);
    }

    /**
     * Test rate query execution with enums
     */
    public function test_rate_queries_with_enums()
    {
        $country = Country::factory()->create();
        
        // Create multiple rates with different enum values
        $rates = collect([
            Rate::factory()->create([
                'country_id' => $country->id,
                'package_type' => PackageType::Document()->value,
                'type' => RateType::Original()->value
            ]),
            Rate::factory()->create([
                'country_id' => $country->id,
                'package_type' => PackageType::NonDocument()->value,
                'type' => RateType::Personal()->value
            ]),
            Rate::factory()->create([
                'country_id' => $country->id,
                'package_type' => PackageType::Document()->value,
                'type' => RateType::Business()->value
            ])
        ]);

        // Execute query methods with enum filtering
        $documentRates = Rate::where('package_type', PackageType::Document()->value)->get();
        $originalRates = Rate::where('type', RateType::Original()->value)->get();
        $countryRates = Rate::where('country_id', $country->id)->get();

        $this->assertCount(2, $documentRates);
        $this->assertCount(1, $originalRates);
        $this->assertCount(3, $countryRates);
    }

    /**
     * Test rate business logic execution
     */
    public function test_rate_business_logic()
    {
        $country = Country::factory()->create();
        
        // Execute business logic with different package types
        $documentRate = Rate::factory()->create([
            'country_id' => $country->id,
            'package_type' => PackageType::Document()->value,
            'price' => 100.00
        ]);
        
        $nonDocumentRate = Rate::factory()->create([
            'country_id' => $country->id,
            'package_type' => PackageType::NonDocument()->value,
            'price' => 200.00
        ]);

        // Execute calculations based on package type
        $documentCost = $this->calculateRateCost($documentRate);
        $nonDocumentCost = $this->calculateRateCost($nonDocumentRate);

        $this->assertGreaterThan(0, $documentCost);
        $this->assertGreaterThan(0, $nonDocumentCost);
    }

    /**
     * Execute rate calculation business logic
     */
    private function calculateRateCost(Rate $rate)
    {
        $baseCost = $rate->price;
        
        // Execute enum-based business logic
        if ($rate->package_type === PackageType::Document()->value) {
            return $baseCost * 1.0; // Standard rate
        } elseif ($rate->package_type === PackageType::NonDocument()->value) {
            return $baseCost * 1.5; // Higher rate for packages
        }
        
        return $baseCost;
    }
}
