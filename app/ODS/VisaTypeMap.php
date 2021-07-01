<?php

namespace App\ODS;

use Illuminate\Database\Eloquent\Model;

class VisaTypeMap extends Model
{
    protected $table = 'ods.visa_type_map';
    protected $connection = 'SAO';
    protected $primaryKey = 'RCID';

    public $timestamps = 'true';
}
