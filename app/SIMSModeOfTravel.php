<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SIMSModeOfTravel extends Model
{
  use SoftDeletes;

  protected $table = "sims.mode_of_travel";
  protected $primaryKey = 'id';
}
