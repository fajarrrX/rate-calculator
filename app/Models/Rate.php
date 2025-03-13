<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    const NAME = 'Rate';

    protected $fillable = [
        'country_id',
        'package_type',
        'weight',
        'zone',
        'type',
        'price',
    ];
    
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
