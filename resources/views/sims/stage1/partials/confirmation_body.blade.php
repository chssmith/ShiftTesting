<h2 style="margin-top: 5px;"> Orientation Session Confirmation </h2>
@if (!empty($registered_session->session_dates))
  <p>
    Thank you for reserving your spot at Roanoke College’s Summer Orientation <em>Session {{$registered_session->session_dates->id}}: {{ $registered_session->session_dates->start_date->format("F jS") }} &ndash; {{ $registered_session->session_dates->end_date->format("jS") }}</em>!
  </p>

  <p>
    More details will be sent to you about the new virtual format by June 1.
  </p>
@else
  <p>
    We're sorry we won't see you this Summer!  More information will follow pertaining to your class schedules, etc.
  </p>
@endif

<p>
  Please continue the enrollment process on your To Do List, starting with the <a href="https://aepweb.roanoke.edu">Annual Enrollment Profile</a> and through your <a href="https://inquire.roanoke.edu">Inquire</a> modules.
  We understand test scores and final transcripts may be delayed, however many items are best completed by June 1.
</p>

<p>
  Watch your <a href="http://mymail.roanoke.edu">Roanoke College Email</a> for further communications pertaining to your orientation schedules.
</p>
