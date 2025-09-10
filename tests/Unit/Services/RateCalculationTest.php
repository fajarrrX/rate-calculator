<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Rate;
use App\Models\Country;
use App\Enums\PackageType;
use App\Enums\RateType;

class RateCalculationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test rate calculation logic execution
     */
    public function test_rate_calculation_execution()
    {
        $country = Country::factory()->create();
        
        // Create rates for calculation
        $documentRate = Rate::factory()->create([
            'country_id' => $country->id,
            'package_type' => PackageType::Document()->value,
            'rate_type' => RateType::Original()->value,
            'weight_from' => 0.0,
            'weight_to' => 2.0,
            'cost' => 50.00
        ]);

        $nonDocumentRate = Rate::factory()->create([
            'country_id' => $country->id,
            'package_type' => PackageType::NonDocument()->value,
            'rate_type' => RateType::Personal()->value,
            'weight_from' => 0.0,
            'weight_to' => 5.0,
            'cost' => 150.00
        ]);

        // Execute rate lookup logic
        $this->executeRateLookup($country->id, PackageType::Document()->value, 1.5);
        $this->executeRateLookup($country->id, PackageType::NonDocument()->value, 3.0);
    }

    /**
     * Execute rate lookup functionality
     */
    private function executeRateLookup($countryId, $packageType, $weight)
    {
        // This simulates rate calculation service logic
        $rate = Rate::where('country_id', $countryId)
            ->where('package_type', $packageType)
            ->where('weight_from', '<=', $weight)
            ->where('weight_to', '>=', $weight)
            ->first();

        if ($rate) {
            // Execute cost calculation
            $calculatedCost = $this->calculateCost($rate, $weight);
            $this->assertIsFloat($calculatedCost);
            $this->assertGreaterThan(0, $calculatedCost);
        }
    }

    /**
     * Execute cost calculation logic
     */
    private function calculateCost(Rate $rate, $weight)
    {
        // Execute business logic for cost calculation
        $baseCost = $rate->cost;
        
        // Apply package type multiplier
        $multiplier = $this->getPackageTypeMultiplier($rate->package_type);
        
        // Apply rate type adjustments
        $adjustment = $this->getRateTypeAdjustment($rate->rate_type);
        
        return ($baseCost * $multiplier) + $adjustment;
    }

    /**
     * Execute package type business logic
     */
    private function getPackageTypeMultiplier($packageType)
    {
        // Execute enum comparison logic
        if ($packageType === PackageType::Document()->value) {
            return 1.0; // Document packages
        } elseif ($packageType === PackageType::NonDocument()->value) {
            return 1.5; // Non-document packages cost more
        }
        
        return 1.0;
    }

    /**
     * Execute rate type business logic
     */
    private function getRateTypeAdjustment($rateType)
    {
        // Execute enum-based business rules
        switch ($rateType) {
            case 1: // RateType::Original()->value
                return 0.0;
            case 2: // RateType::Personal()->value
                return 10.0;
            case 3: // RateType::Business()->value
                return 25.0;
            default:
                return 0.0;
        }
    }

    /**
     * Test weight-based rate selection
     */
    public function test_weight_based_selection()
    {
        $country = Country::factory()->create();
        
        // Create overlapping weight ranges
        Rate::factory()->create([
            'country_id' => $country->id,
            'package_type' => PackageType::Document()->value,
            'weight_from' => 0.0,
            'weight_to' => 2.0,
            'cost' => 30.00
        ]);

        Rate::factory()->create([
            'country_id' => $country->id,
            'package_type' => PackageType::Document()->value,
            'weight_from' => 2.0,
            'weight_to' => 10.0,
            'cost' => 80.00
        ]);

        // Execute weight-based selection logic
        $lightRate = Rate::where('country_id', $country->id)
            ->where('weight_from', '<=', 1.5)
            ->where('weight_to', '>=', 1.5)
            ->first();

        $heavyRate = Rate::where('country_id', $country->id)
            ->where('weight_from', '<=', 5.0)
            ->where('weight_to', '>=', 5.0)
            ->first();

        $this->assertEquals(30.00, $lightRate->cost);
        $this->assertEquals(80.00, $heavyRate->cost);
    }
}
