@php
  use \Carbon\Carbon;
  $now          = Carbon::parse();
  $window_start = Carbon::parse("2020-02-11 9:30 PM");
  $window_end   = Carbon::parse("2020-02-12 3:00 AM");
@endphp
@if ($now >= $window_start && $now <= $window_end)
  <div style="padding-left: 20px;">
    <p style="margin-bottom: 0px">This form will be down for routine maintenance on February 11<sup>th</sup>, 2020 at 10:00 PM EST &ndash; February 12<sup>th</sup>, 2020 at 02:00 AM EST.</p>
    <p style="margin-top: 0px">Any data input before the maintenance window will be saved and the forms can be completed after the end of the maintenance window.</p>
  </div>
@else
  Something went wrong.  Please contact <a href="mailto:orientation@roanoke.edu">orientation@roanoke.edu</a> to inform them of your error.
@endif

<p style="margin-bottom: 0px">
  <strong style="font-weight: bold">Error Message: </strong>
  <div>
    {{$exception->getMessage()}}
  </div>
</p>
