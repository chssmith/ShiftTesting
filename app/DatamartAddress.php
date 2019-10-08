<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatamartAddress extends model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    protected $table      = 'dbo.Address';
    protected $primaryKey = 'key_AddressId';
    protected $connection = 'DataMart';

}