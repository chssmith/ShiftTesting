<?php

namespace App\ODS;

use Illuminate\Database\Eloquent\Model;

class USResidence extends Model
{
    protected $table = 'ods.us_residence';
    protected $primaryKey = 'RCID';
    protected $connection = 'SAO';

    public $incrementing = false;
    public $timestamps = false;
}
