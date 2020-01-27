<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Students extends Model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    use SoftDeletes;
    protected $table      = 'student_forms.students';
    protected $connection = 'SAO';
    protected $primaryKey = "RCID";
    protected $appends    = ['address'];
    protected $fillable   = ["RCID"];

    public $incrementing  = false;

    public function local_percs () {
      return $this->hasMany("App\PERC", "rcid", "RCID");
    }

    public function ods_residence () {
      return $this->hasOne("\App\ODS\USResidence", "RCID", "RCID");
    }

    public function ods_citizenship () {
      return $this->hasOne("\App\ODS\CitizenshipInformation", "fkey_rcid", "RCID");
    }

    public function parents(){
    	return $this->hasMany('App\GuardianInfo', 'student_rcid', 'RCID');
    }

    public function datamart_home_address () {
      return $this->hasOne("App\DatamartAddress", "RCID", "RCID")->where("fkey_AddressTypeId", 1);
    }

    public function datamart_billing_address () {
      return $this->hasOne("App\DatamartAddress", "RCID", "RCID")->where("fkey_AddressTypeId", 3);
    }

    public function datamart_local_address () {
      return $this->hasOne("App\DatamartAddress", "RCID", "RCID")->where("fkey_AddressTypeId", 4);
    }

    public function home_address(){
    	return $this->hasOne('App\Address','RCID', 'RCID')->where('fkey_AddressTypeId', 1);
    }

    public function billing_address(){
    	return $this->hasOne('App\Address','RCID', 'RCID')->where('fkey_AddressTypeId', 3);
    }

    public function local_address(){
    	return $this->hasOne('App\Address','RCID', 'RCID')->where('fkey_AddressTypeId', 4);
    }

    public function visa(){
    	return $this->hasOne('App\VisaTypeMap', 'RCID', 'RCID');
    }

    public function citizenship() {
      return $this->hasOne("App\CitizenshipInformation", "fkey_rcid");
    }

    public function getDatamartAddressAttribute ($value) {
      $billing = $this->datamart_billing_address;
      if(!empty($billing)) $billing = $billing->load("state");
      $local   = $this->datamart_local_address;
      if(!empty($local)) $local = $local->load("state");
    	return collect(['Home'=>GenericAddress::fromMixedAddressForReport($this->datamart_home_address->load("state")),
                      'Billing'=>GenericAddress::fromMixedAddressForReport($billing),
                      'Local'=>GenericAddress::fromMixedAddressForReport($local)]);
    }

    public function getAddressAttribute($value){
      $billing = $this->billing_address;
      $local   = $this->local_address;
      if ($this->home_as_billing) $billing = $this->home_address;
      if ($this->home_as_local)   $local   = $this->home_address;
    	return (['Home'=>$this->home_address, 'Billing'=>$billing, 'Local'=>$local]);
    }

    public function ap_exams () {
      return $this->hasManyThrough("\App\APExams", "\App\APMap", "rcid", "id", "RCID", "fkey_ap_exam");
    }

    public function ib_exams () {
      return $this->hasManyThrough("\App\IBExams", "\App\IBMap", "rcid", "id", "RCID", "fkey_ib_exam");
    }

    public function de_courses () {
      return $this->hasManyThrough("\App\DualEnrollmentCourses", "\App\DEMap", "rcid", "id", "RCID", "fkey_dual_enrollment_course");
    }

    public function prospect_status () {
      return $this->hasOne ("\App\StudentProspect", "key_ProspectId", "RCID");
    }

    public function datamart_user () {
      return $this->hasOne("\App\DatamartStudent", "RCID", "RCID");
    }
}
