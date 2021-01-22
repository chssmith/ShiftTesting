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
      return $this->hasOne("App\MaritalStatuses", "code", "fkey_marital_status");
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

    public function guardian_type () {
      return $this->hasOne("App\GuardianRelationshipTypes", "id", "relationship");
    }

    public function ods_guardian () {
      return $this->hasOne("\App\ODS\GuardianInfo", "fkey_parent_rcid", "fkey_parent_rcid");
    }

    public function getDisplayNameAttribute () {
      $name = $this->first_name;
      if (!empty($this->nick_name)) {
        $name = $this->nick_name;
      }
      return sprintf("%s %s", $name, $this->last_name);
    }

    public function home_address () {
    	return $this->hasOne('App\Address','RCID', 'RCID')->where('fkey_AddressTypeId', 1);
    }

    public function complete () {
      return !empty($this->first_name) && !empty($this->last_name) &&
             !empty($this->fkey_marital_status) && !empty($this->relationship) &&
             (!empty($this->email) || !empty($this->home_phone) || !empty($this->cell_phone)) &&
             !empty($this->fkey_CountryId) && !empty($this->fkey_education_id) &&
             GenericAddress::fromGuardianInfo($this)->complete();
    }

    public function getMissingInformation () {
      $messages = collect();
      $name = "Guardian";

      if (empty($this->first_name) || empty($this->last_name)) {
        $messages[] = "Guardian name is missing";
      } else {
        $name = "$this->first_name $this->last_name";
      }

      if (empty($this->marital_status)) {
        $messages[] = "$name is missing Marital Status";
      }

      if (empty($this->relationship)) {
        $messages[] = "$name is missing relationship to student";
      }

      if (empty($this->email) && empty($this->home_phone) && empty($this->cell_phone)) {
        $messages[] = "$name is missing at least one piece of contact information";
      }

      GenericAddress::fromGuardianInfo($this)->getMissingInformation()->reduce(function ($collector, $item) use ($name) {
        $collector[] = sprintf("%s %s", $name, $item);
        return $collector;
      }, $messages);

      return $messages;
    }
}
