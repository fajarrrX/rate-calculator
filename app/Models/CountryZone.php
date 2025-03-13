<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'name',
        'zone',
        'transit_day'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
