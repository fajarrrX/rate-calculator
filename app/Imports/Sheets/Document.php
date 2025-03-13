<?php

namespace App\Imports\Sheets;

use App\Enums\PackageType;
use App\Enums\RateType;
use App\Models\Rate;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class Document implements ToCollection, WithHeadingRow
{
    protected $country;
    protected $type;

    public function __construct($country, $type)
    {
        $this->country = $country;
        $this->type = $type;
    }
    
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach($collection as $row){
            $newArray = [];

            foreach ($row as $key => $value) {
                if (strpos($key, 'zone_') === 0) {
                    $newKey = str_replace('zone_', '', $key);
                    $newArray[$newKey] = $value;
                }
            }

            foreach($newArray as $key => $rate){
                Rate::updateOrCreate([
                    'country_id' => $this->country->id,
                    'type' => $this->type,
                    'package_type' => PackageType::Document,
                    'zone' => $key,
                    'weight' => $row['kg'],
                ], [
                    'price' => $rate,
                ]);
            }
        }
    }
}