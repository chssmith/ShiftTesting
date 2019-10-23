<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Countries extends Model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    protected $table      = 'dbo.Countries';
    protected $primaryKey = 'key_CountryId';
    protected $connection = 'DataMart';

}
