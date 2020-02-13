{{-- Tabs to go to each section --}}
<div class="btn-group btn-group-justified" role="group">
  <a href="{{action("SIMSRegistrationController@studentInfoPage")}}" class="btn btn-primary">Student Info</a>
  <a href="{{action("SIMSRegistrationController@parentsGuestsPage")}}" class="btn btn-primary">Guests</a>
  <a href="{{action("SIMSRegistrationController@modeOfTravelPage")}}" class="btn btn-primary">Mode of Travel</a>
  <a href="{{action("SIMSRegistrationController@confirmationPage")}}" class="btn btn-primary">Confirmation</a>
</div>
<h4 class="pull-right">{!! $session_dates !!}</h4>
