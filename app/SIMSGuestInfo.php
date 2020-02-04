<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SIMSGuestInfo extends Model
{
  use SoftDeletes;

  protected $table = "sims.guest_info";
  protected $primaryKey = 'id';
}
