<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatamartPhones extends Model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    protected $table = 'dbo.Phone';
    protected $primaryKey = 'key_PhoneId';
    protected $connection = 'DataMart';
}
