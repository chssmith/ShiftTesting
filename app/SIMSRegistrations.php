<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SIMSRegistrations extends Model
{
  use SoftDeletes;

  protected $table = "student_forms.sims_registrations";
  protected $primaryKey = 'id';
}
