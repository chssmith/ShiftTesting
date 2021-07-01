<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    use SoftDeletes;
    protected $table = 'student_forms.address';
    protected $primaryKey = 'id';
    protected $connection = 'SAO';
    protected $fillable = ['RCID', 'fkey_AddressTypeId', 'created_by'];

    public function country_details()
    {
        return $this->hasOne(\App\Countries::class, 'key_CountryId', 'fkey_CountryId');
    }
}
