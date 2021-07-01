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
    protected $table = 'student_forms.students';
    protected $connection = 'SAO';
    protected $primaryKey = 'RCID';
    protected $appends = ['address'];
    protected $fillable = ['RCID'];

    public $incrementing = false;

    public function getDisplayNameAttribute()
    {
        return sprintf('%s %s', $this->first_name, $this->last_name);
    }

    public function admit_status()
    {
        return $this->hasOne(\App\ODS\AdmitStatus::class, 'rcid');
    }

    public function local_percs()
    {
        return $this->hasMany(\App\PERC::class, 'rcid', 'RCID');
    }

    public function ods_residence()
    {
        return $this->hasOne(\App\ODS\USResidence::class, 'RCID', 'RCID');
    }

    public function ods_citizenship()
    {
        return $this->hasOne(\App\ODS\CitizenshipInformation::class, 'fkey_rcid', 'RCID');
    }

    public function parents()
    {
        return $this->hasMany(\App\GuardianInfo::class, 'student_rcid', 'RCID');
    }

    public function datamart_home_address()
    {
        return $this->hasOne(\App\DatamartAddress::class, 'RCID', 'RCID')->where('fkey_AddressTypeId', 1);
    }

    public function datamart_billing_address()
    {
        return $this->hasOne(\App\DatamartAddress::class, 'RCID', 'RCID')->where('fkey_AddressTypeId', 3);
    }

    public function datamart_local_address()
    {
        return $this->hasOne(\App\DatamartAddress::class, 'RCID', 'RCID')->where('fkey_AddressTypeId', 4);
    }

    public function home_address()
    {
        return $this->hasOne(\App\Address::class, 'RCID', 'RCID')->where('fkey_AddressTypeId', 1);
    }

    public function billing_address()
    {
        return $this->hasOne(\App\Address::class, 'RCID', 'RCID')->where('fkey_AddressTypeId', 3);
    }

    public function local_address()
    {
        return $this->hasOne(\App\Address::class, 'RCID', 'RCID')->where('fkey_AddressTypeId', 4);
    }

    public function visa()
    {
        return $this->hasOne(\App\VisaTypeMap::class, 'RCID', 'RCID');
    }

    public function citizenship()
    {
        return $this->hasOne(\App\CitizenshipInformation::class, 'fkey_rcid');
    }

    public function getDatamartAddressAttribute($value)
    {
        $billing = $this->datamart_billing_address;
        if (! empty($billing) && ! empty($billing->fkey_StateId) && empty($billing->state)) {
            $billing = $billing->load('state');
        }
        $local = $this->datamart_local_address;
        if (! empty($local) && ! empty($local->fkey_StateId) && empty($local->state)) {
            $local = $local->load('state');
        }

        return collect(['Home'=>GenericAddress::fromMixedAddressForReport($this->datamart_home_address),
                      'Billing'=>GenericAddress::fromMixedAddressForReport($billing),
                      'Local'=>GenericAddress::fromMixedAddressForReport($local), ]);
    }

    public function getAddressAttribute($value)
    {
        $billing = $this->billing_address;
        $local = $this->local_address;
        if ($this->home_as_billing) {
            $billing = $this->home_address;
        }
        if ($this->home_as_local) {
            $local = $this->home_address;
        }

        return ['Home'=>$this->home_address, 'Billing'=>$billing, 'Local'=>$local];
    }

    public function ap_exams()
    {
        return $this->hasManyThrough(\App\APExams::class, \App\APMap::class, 'rcid', 'id', 'RCID', 'fkey_ap_exam');
    }

    public function ib_exams()
    {
        return $this->hasManyThrough(\App\IBExams::class, \App\IBMap::class, 'rcid', 'id', 'RCID', 'fkey_ib_exam');
    }

    public function de_courses()
    {
        return $this->hasManyThrough(\App\DualEnrollmentCourses::class, \App\DEMap::class, 'rcid', 'id', 'RCID', 'fkey_dual_enrollment_course');
    }

    public function prospect_status()
    {
        return $this->hasOne(\App\StudentProspect::class, 'key_ProspectId', 'RCID');
    }

    public function datamart_user()
    {
        return $this->hasOne(\App\DatamartStudent::class, 'RCID', 'RCID');
    }

    public function ods_student()
    {
        return $this->hasOne(\App\ODS\Students::class, 'RCID', 'RCID');
    }

    public function ssn()
    {
        return $this->hasOne(\App\HaveSSN::class, 'ID', 'RCID');
    }

    public function scopeFinished($query)
    {
        $query->whereHas('local_percs', function ($query) {
            $query->where('perc', 'LIKE', '%RSI%')->withTrashed();
        });
    }
}
