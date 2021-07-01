<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatamartAddress extends Model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    protected $table = 'dbo.Address';
    protected $primaryKey = 'key_AddressId';
    protected $connection = 'DataMart';

    public function state()
    {
        return $this->hasOne("App\States", 'key_StateId', 'fkey_StateId');
    }

    public function country()
    {
        return $this->hasOne("App\Countries", 'key_CountryId', 'fkey_CountryId');
    }
}
