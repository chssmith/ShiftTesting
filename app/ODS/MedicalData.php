<?php

namespace App\ODS;

use Illuminate\Database\Eloquent\Model;

class MedicalData extends Model
{
    const     HEADERS       = ["medications" => "Medicines -", "med_allergy" => "Med Allergies -", "insect_allergy" => "Insect/Food Allergies", "other" => "Other -"];
    protected $table        = "ods.medical_data";
    protected $primaryKey   = "PERSON_EMER_ID";
    protected $connection   = "SAO";
    public    $incrementing = false;

    public function pull_data($header_key) {
      $header         = self::HEADERS[$header_key];
      $starting_point = strpos($this->EMER_ADDNL_INFORMATION, $header);
      if ($starting_point !== False) {
        $starting_point +=  strlen($header);
        $ending_points  = [strlen($this->EMER_ADDNL_INFORMATION)];
        foreach (self::HEADERS as $local_header) {
          $val = strpos($this->EMER_ADDNL_INFORMATION, $local_header, $starting_point);
          if(!empty($val)) {
            $ending_points[] = $val;
          }
        }
        return trim(substr($this->EMER_ADDNL_INFORMATION, $starting_point, min($ending_points) - $starting_point));
      } else {
        return "";
      }
    }
}
