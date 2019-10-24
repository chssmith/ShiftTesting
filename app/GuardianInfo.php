<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuardianInfo extends Model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    use SoftDeletes;
    protected $table      = 'student_forms.guardian_info';
    protected $primaryKey = 'id';
    protected $connection = 'SAO';
    protected $fillable   = ['student_rcid', 'created_by'];

     public function employment(){
    	return $this->hasOne('App\EmploymentInfo', 'fkey_guardian_id', 'id');
    }

    public function getDisplayNameAttribute () {
      $name = $this->first_name;
      if (!empty($this->nick_name)) {
        $name = $this->nick_name;
      }
      return sprintf("%s %s", $name, $this->last_name);
    }
}
