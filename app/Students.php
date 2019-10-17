<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Students extends model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    use SoftDeletes;
    protected $table      = 'student_forms.students';
    protected $connection = 'SAO';
    protected $appends    = ['address'];
    protected $fillable   = ["RCID"];


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

    public function getAddressAttribute($value){
    	$home_address    = $this->home_address();
    	$billing_address = $this->billing_address();
    	$local_address   = $this->local_address();

    	return (['Home'=>$home_address, 'Billing'=>$billing_address, 'Local'=>$local_address]);
    }

}
