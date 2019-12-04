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


    public function parents(){
    	return $this->hasMany('App\GuardianInfo', 'student_rcid', 'RCID');
    }

    public function home_address(){
    	return $this->hasOne('App\Address','RCID', 'RCID')->where('fkey_AddressTypeId', 1)->first();
    }

    public function billing_address(){
    	return $this->hasOne('App\Address','RCID', 'RCID')->where('fkey_AddressTypeId', 3)->first();
    }

    public function local_address(){
    	return $this->hasOne('App\Address','RCID', 'RCID')->where('fkey_AddressTypeId', 4)->first();
    }

    public function visa(){
    	return $this->hasOne('App\VisaTypeMap', 'RCID', 'RCID');
    }

    public function citizenship() {
      return $this->hasOne("App\CitizenshipInformation", "fkey_rcid");
    }

    public function getAddressAttribute($value){
    	$home_address    = $this->home_address();
    	$billing_address = $this->billing_address();
    	$local_address   = $this->local_address();

    	return (['Home'=>$home_address, 'Billing'=>$billing_address, 'Local'=>$local_address]);
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
}
