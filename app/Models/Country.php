<?php

namespace App\Models;

use App\Enums\PackageType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use \OwenIt\Auditing\Auditable as AuditTrait;

class Country extends Model implements Auditable
{
    use HasFactory, AuditTrait;

    const NAME = 'Country';

    protected $fillable = ['name', 'code', 'currency_code', 'decimal_places', 'symbol_after_personal_price', 'symbol_after_business_price', 'hide_package_opt_en', 'hide_package_opt_local', 'hide_step_1', 'share_country_id'];

    public function receivers()
    {
        if($this->share_country_id){
            //get receiver based on share country id
            return $this->hasMany(CountryZone::class, 'country_id', 'share_country_id');
        }
        return $this->hasMany(CountryZone::class);
    }

    public function rates()
    {
        if($this->share_country_id){
            //get rate based on share country id
            return $this->hasMany(Rate::class, 'country_id', 'share_country_id');
        }

        return $this->hasMany(Rate::class);
    }

    public function share_country()
    {
        return $this->belongsTo(Country::class, 'share_country_id');
    }

    public function quote_langs()
    {
        return $this->hasMany(CountryQuoteLang::class);
    }

    public function valid_fields()
    {
        return (new CountryQuoteLang)->valid_fields();
    }

    public function replace_fields()
    {
        return (new CountryQuoteLang)->replace_fields();
    }

    public function static_fields()
    {
        return (new CountryQuoteLang)->static_fields();
    }

    public function getNonDocMaxWeightAttribute()
    {
        return (double)$this->rates->where('package_type', PackageType::NonDocument)->max('weight');
    }

    public function getDocMaxWeightAttribute()
    {
        return (double)$this->rates->where('package_type', PackageType::Document)->max('weight');
    }
}