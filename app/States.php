<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class States extends model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    protected $table      = 'dbo.States';
    protected $primaryKey = 'key_StateId';
    protected $connection = 'DataMart';
}