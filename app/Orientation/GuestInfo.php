<?php

namespace App\Orientation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuestInfo extends Model
{
  use SoftDeletes;

  protected $table = "orientation.guest_info";
  protected $primaryKey = 'id';
}
