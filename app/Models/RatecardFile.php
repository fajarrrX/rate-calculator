<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatecardFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'name',
        'path',
        'type'
    ];

    const NAME = 'Ratecard File';
    
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}