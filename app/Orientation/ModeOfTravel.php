<?php

namespace App\Orientation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModeOfTravel extends Model
{
  use SoftDeletes;

  protected $table = "orientation.mode_of_travel";
  protected $primaryKey = 'id';
}
