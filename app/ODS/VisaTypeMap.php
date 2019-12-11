<?php

namespace App\ODS;

use Illuminate\Database\Eloquent\Model;

class VisaTypeMap extends Model
{
  protected $table      = "student_forms.ods_visa_type_map";
  protected $connection = 'SAO';
  protected $primaryKey = "RCID";

  public $timestamps    = "true";
}
