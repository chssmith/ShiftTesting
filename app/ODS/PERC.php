<?php

namespace App\ODS;

use Illuminate\Database\Eloquent\Model;

class PERC extends Model
{
    protected $table      = "Staging.dbo.student_perc_notifications";
    protected $connection = "SAO";
}
