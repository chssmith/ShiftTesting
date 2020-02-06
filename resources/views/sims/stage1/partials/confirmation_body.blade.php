<h2 style="margin-top: 5px;"> Orientation Session Confirmation </h2>
@if (!empty($registered_session->session_dates))
  <p>
    Thank you for reserving your spot at Roanoke College’s Summer Orientation <em>Session {{$registered_session->session_dates->id}}: {{ $registered_session->session_dates->start_date->format("F jS") }} &ndash; {{ $registered_session->session_dates->end_date->format("jS") }}</em>!
  </p>

  <p>
    Once you have determined your travel details, please complete the Summer
    Orientation Registration in your <a href="{{ action("StudentInformationController@index") }}">Annual Enrollment Profile</a> no later than June 1.
  </p>
@else
  <p>
    We're sorry we won't see you this Summer!  More information will follow pertaining to your class schedules, etc.
  </p>
@endif

<p>
  If you have additional questions, please refer to the <a
  href="https://www.roanoke.edu/admissions/first_year_experience/summer_orientation">First Year Experience Summer Orientation
  Frequently Asked Questions website</a>.
</p>

<p>
  Watch your <a href="http://mymail.roanoke.edu">Roanoke College Email</a> for further communications pertaining to your orientation schedules.
</p>
