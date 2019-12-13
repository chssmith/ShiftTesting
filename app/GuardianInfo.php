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

    public function marital_status () {
      return $this->hasOne("App\MaritalStatuses", "key_maritalStatus", "fkey_marital_status");
    }

    public function country () {
      return $this->hasOne("App\Countries", "key_CountryId", "fkey_CountryId");
    }

    public function state () {
      return $this->hasOne("App\States", "StateCode", "fkey_StateCode");
    }

    public function education () {
      return $this->hasOne("App\Education", "id", "fkey_education_id");
    }

    public function getDisplayNameAttribute () {
      $name = $this->first_name;
      if (!empty($this->nick_name)) {
        $name = $this->nick_name;
      }
      return sprintf("%s %s", $name, $this->last_name);
    }

    public function complete () {
      return !empty($this->first_name) && !empty($this->last_name) &&
             !empty($this->fkey_marital_status) && !empty($this->relationship) &&
             (!empty($this->email) || !empty($this->home_phone) || !empty($this->cell_phone)) &&
             !empty($this->fkey_CountryId) && GenericAddress::fromGuardianInfo($this)->complete() &&
             !is_null($this->info_release);
    }
}
