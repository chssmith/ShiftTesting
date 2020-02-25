<?php

namespace App\ODS;

use Illuminate\Database\Eloquent\Model;

class PERC extends Model
{
    protected $table      = "Staging.dbo.cleared_percs";
    protected $connection = "SAO";
}
