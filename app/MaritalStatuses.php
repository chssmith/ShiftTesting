<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaritalStatuses extends model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    protected $table      = 'dbo.MaritalStatus';
    protected $primaryKey = 'key_maritalStatus';
    protected $connection = 'DataMart';

}