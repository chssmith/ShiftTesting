<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailQueue extends Model
{
    use SoftDeletes;

    protected $table = "ITOperations.email.queue";

    public function getDateFormat()
    {
        return 'Y-m-d H:i:s';
    }

    public static function sendEmail ($to_address, $subject, $body) {
      $email = new EmailQueue;
      $email->to_email   = $to_address;
      $email->from_email = "registrar@roanoke.edu";
      $email->subject    = $subject;
      $email->body       = $body;
      $email->template   = "campusmailer.official";
      $email->created_by = $email->updated_by = "0000001";
      $email->save();
    }
}
