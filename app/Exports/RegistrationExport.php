<?php

namespace App\Exports;

use App\Orientation\Registrations;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RegistrationExport implements FromView
{
    public function view(): View
    {
      $all_registrations = Registrations::with(["session_dates", "student", "student_info", "guests", "mode_of_travel"])->whereNotNull("fkey_mode_of_travel_id")->get();
      $max_guests = \DB::select(\DB::raw("SELECT MAX(guests) AS max_guests FROM (SELECT count(id) AS guests FROM orientation.guest_info WHERE deleted_at IS NULL GROUP BY fkey_registration_id) sub_query"));
      $max_guests = $max_guests[0]->max_guests;

      return view()->make("sims.admin.stage2.partials.report_table", compact("all_registrations", "max_guests"));
    }
}
