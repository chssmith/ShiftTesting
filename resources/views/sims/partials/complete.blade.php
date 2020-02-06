<div>
  <h3>You have successfully registered for the {{$session_dates}} summer orientation.</h3>
</div>
<div>
  <h3>What was submitted</h3>
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
