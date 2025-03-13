<?php

namespace App\Imports\Sheets;

use App\Models\CountryZone;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class Zone implements ToCollection, WithHeadingRow
{
    protected $country;

    public function __construct($country)
    {
        $this->country = $country;
    }
    
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach($collection as $row){
            CountryZone::updateorCreate([
                'country_id' => $this->country->id,
                'name' => $row['country'],
            ], [
                'zone' => $row['zone'],
                'transit_day' => isset($row['transit_day']) ? $row['transit_day'] : null,
            ]);
        }
    }
}
