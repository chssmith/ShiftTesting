<div>
  <h3>You have successfully registered for the {{$session_dates}} summer orientation.</h3>
</div>
<div>
  <p>
    Thank you for completing the full Summer Orientation Registration.  As we begin to plan for your arrival, we understand that unforeseen circumstance may occur requiring a change to these details.  Should this apply to you, please let us know as soon as possible in order for us to accommodate your need and others effected.  <a href="orientation@roanoke.edu">orientation@roanoke.edu</a>
  </p>
  <p>
    If you have additional questions, please refer to the First Year Experience Summer Orientation <a href="https://www.roanoke.edu/admissions/first_year_experience/summer_orientation">Frequently Asked Questions</a> website.
  </p>
  <h4>Student Info:</h4>
  <p>Preferred Name: {{$student_info->nick_name}}</p>
  <p>Gender (for room assignment purposes): {{$student_info->gender}}</p>
  <p>Cell Phone: {{$student_info->cell_phone}}</p>
  @if(isset($student_info->dietary_needs))
    <p>Dietary Needs: {{$student_info->dietary_needs}}</p>
  @endif
  @if(isset($student_info->physical_needs))
    <p>Physical Needs: {{$student_info->physical_needs}}</p>
  @endif
  @foreach($guests as $key => $guest)
    <h4>Guest {{$key+1}}:</h4>
    <p>Relationship: {{$guest->relationship}}</p>
    <p>First Name: {{$guest->first_name}}</p>
    <p>Last Name: {{$guest->last_name}}</p>
    <p>Email: {{$guest->email}}</p>
    @if(isset($guest->dietary_needs))
      <p>Dietary Needs: {{$guest->dietary_needs}}</p>
    @endif
    @if(isset($guest->physical_needs))
      <p>Physical Needs: {{$guest->physical_needs}}</p>
    @endif
    @if($guest->on_campus)
      <p>Staying on Campus: Yes</p>
    @else
      <p>Staying on Campus: No</p>
    @endif
  @endforeach
  <h4>Mode of Travel:</h4>
  <p>Mode of Travel: {{$mot}}</p>
  <p>Using the Shuttle: @if($shuttle)Yes @else No @endif</p>
</div>
