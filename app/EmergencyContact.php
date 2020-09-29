<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmergencyContact extends Model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    use SoftDeletes;
    protected $table      = 'student_forms.emergency_contact';
    protected $primaryKey = 'id';
    protected $connection = 'SAO';
    protected $fillable   = ["student_rcid", "created_by", "missing_person"];

    public function completed () {
      return !empty($this->name) && !empty($this->relationship) && (!empty($this->day_phone) || !empty($this->evening_phone) || !empty($this->cell_phone));
    }

    public function getMissingInformation () {
      $messages =  collect();
      if (empty($this->name)) {
        $messages[] = "Emergency contact missing a name";
      }

      if (empty($this->relationship)) {
        $messages[] = "Emergency contact missing relationship";
      }

      if (empty($this->day_phone) && empty($this->evening_phone) && empty($this->cell_phone)) {
        $messages[] = "Emergency contact missing at least one contact number";
      }

      return $messages;
    }
}
