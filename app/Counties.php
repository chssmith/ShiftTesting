<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Counties extends model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    protected $table      = 'dbo.counties';
    protected $primaryKey = 'county_id';
    protected $connection = 'DataMart';

    public $incrementing = false;

}