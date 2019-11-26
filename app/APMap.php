<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class APMap extends Model
{
  use SoftDeletes;
  protected $table = 'student_forms.ap_map';
}
