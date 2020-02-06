<?php

namespace App\Exports;

use App\SIMSRegistrations;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SIMSRegistrationExport implements FromView
{
    public function view(): View
    {
      $all_registrations = SIMSRegistrations::with(["session_dates", "student"])->get();

      return view()->make("sims.admin.stage1.partials.report_table", compact("all_registrations"));
    }
}
