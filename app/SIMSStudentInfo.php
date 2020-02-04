<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SIMSStudentInfo extends Model
{
  use SoftDeletes;

  protected $table = "sims.student_info";
  protected $primaryKey = 'id';
}
