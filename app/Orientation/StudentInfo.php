<?php

namespace App\Orientation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentInfo extends Model
{
  use SoftDeletes;

  protected $table      = "orientation.student_info";
  protected $primaryKey = 'id';
  public $fillable      = ['rcid', 'created_by'];
}
