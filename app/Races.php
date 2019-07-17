<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Races extends model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    protected $table      = 'dbo.race';
    protected $primaryKey = 'code';
    protected $connection = 'DataMart';

    public $incrementing = false;

}