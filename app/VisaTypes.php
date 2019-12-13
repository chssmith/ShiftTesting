<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VisaTypes extends Model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    protected $table      = 'dbo.VisaTypes';
    protected $primaryKey = 'code';
    protected $connection = 'DataMart';

    public $incrementing = false;

}
