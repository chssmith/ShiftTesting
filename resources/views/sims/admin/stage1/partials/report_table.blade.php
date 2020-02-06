<table class="datatable table-bordered table-condensed dark_table">
  <thead>
    <tr>
      <th>RCID</th>
      <th>Name</th>
      <th>Registered Session</th>
      <th>Date Registered</th>
      <th>Not Coming?</th>
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
          {{ $registration->session_dates->start_date->format("F jS") }} - {{ $registration->session_dates->end_date->format("jS") }}
        </td>
        <td>
          {{ $registration->created_at->format('n/j/Y g:i a') }}
        </td>
        <td>
          {{ $registration->cannot_attend ? "Y" : "N" }}
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
