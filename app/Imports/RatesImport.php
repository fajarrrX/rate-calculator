<?php

namespace App\Imports;

use App\Imports\Sheets\Document;
use App\Imports\Sheets\Parcel;
use App\Imports\Sheets\Zone;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RatesImport implements WithMultipleSheets
{
    protected $country;
    protected $type;

    public function __construct($country, $type)
    {
        $this->country = $country;
        $this->type = $type;
    }

    public function sheets(): array
    {
        return [
            'Documents' => new Document($this->country, $this->type),
            'Non Documents' => new Parcel($this->country, $this->type),
            'Zones' => new Zone($this->country),
        ];
    }
}