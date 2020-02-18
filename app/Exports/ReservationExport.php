<?php

namespace App\Exports;

use App\Orientation\Registrations;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReservationExport implements FromView
{
    public function view(): View
    {
      $all_registrations = Registrations::with(["session_dates", "student"])->get();

      return view()->make("sims.admin.stage1.partials.report_table", compact("all_registrations"));
    }
}
