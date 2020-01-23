<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SIMSRegistrations extends Model
{
  use SoftDeletes;

  protected $table = "sims.registrations";
  protected $primaryKey = 'id';
}
