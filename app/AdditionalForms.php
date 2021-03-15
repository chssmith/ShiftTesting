<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class AdditionalForms extends Model
{
    use SoftDeletes;
    protected $table = "student_forms.additional_forms";

    public function getDueDateAttribute ($value) {
      return Carbon::parse($value);
    }

    public function getPerc () {
      $code = $this->perc_prefix;
      if ($this->include_year) {
        $code .= Carbon::now()->format("y");
      }
      return $code;
    }

    public function getPercRegex ($year_postfix) {
      return sprintf("/%s%s/", $this->perc_prefix, ($this->include_year ? $year_postfix : ""));

    }
}
