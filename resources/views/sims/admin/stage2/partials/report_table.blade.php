<table class="datatable table-bordered table-condensed dark_table" style="width: 400px;">
  <thead>
    <tr>
      <th>RCID</th>
      <th>Name</th>
      <th>Registered Session</th>
      <th>Date Registered</th>
      <th>Not Coming?</th>
      <th>Shuttle</th>
      <th>Mode of Travel</th>
      <th>Preferred Name</th>
      <th>Gender</th>
      <th>Cell Phone</th>
      <th>Dietary Needs</th>
      <th>Physical Needs</th>
      @for($i = 1; $i <= $max_guests; $i++)
        <th>Guest {{$i}} Relationship</th>
        <th>Guest {{$i}} First Name</th>
        <th>Guest {{$i}} Last Name</th>
        <th>Guest {{$i}} Email</th>
        <th>Guest {{$i}} Dietary Needs</th>
        <th>Guest {{$i}} Physical Needs</th>
        <th>Guest {{$i}} On Campus</th>
      @endfor
    </tr>
  </thead>
  <tbody>
    @foreach($all_registrations as $registration)
      <tr>
        <td>
          {{ $registration->rcid }}
        </td>
        <td>
          {{ $registration->student->display_name }}
        </td>
        <td>
          @if (!empty($registration->session_dates))
            {{ $registration->session_dates->start_date->format("F jS") }} - {{ $registration->session_dates->end_date->format("jS") }}
          @endif
        </td>
        <td>
          {{ $registration->created_at->format('n/j/Y g:i a') }}
        </td>
        <td>
          {{ $registration->cannot_attend ? "Y" : "N" }}
        </td>
        <td>
          {{ $registration->shuttle?"Y":"N" }}
        </td>
        <td>
          {{ $registration->mode_of_travel->travel_type }}
        </td>
        <td>
          {{ $registration->student_info->nick_name }}
        </td>
        <td>
          {{ $registration->student_info->gender }}
        </td>
        <td>
          {{ $registration->student_info->cell_phone }}
        </td>
        <td>
          {{ $registration->student_info->dietary_needs }}
        </td>
        <td>
          {{ $registration->student_info->physical_needs }}
        </td>
        @for($i = 0; $i < $max_guests; $i++)
          <td>
            {{ isset($registration->guests[$i])?$registration->guests[$i]->relationship:"" }}
          </td>
          <td>
            {{ isset($registration->guests[$i])?$registration->guests[$i]->first_name:"" }}
          </td>
          <td>
            {{ isset($registration->guests[$i])?$registration->guests[$i]->last_name:"" }}
          </td>
          <td>
            {{ isset($registration->guests[$i])?$registration->guests[$i]->email:"" }}
          </td>
          <td>
            {{ isset($registration->guests[$i])?$registration->guests[$i]->dietary_needs:"" }}
          </td>
          <td>
            {{ isset($registration->guests[$i])?$registration->guests[$i]->physical_needs:"" }}
          </td>
          <td>
            {{ isset($registration->guests[$i])?($registration->guests[$i]->on_campus?"Y":"N"):"" }}
          </td>
        @endfor
      </tr>
    @endforeach
  </tbody>
</table>
