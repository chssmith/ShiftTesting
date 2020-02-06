<div class="row">
  <div class="col-xs-12">
    <div id="SIMS" class="list-group">
      @foreach($sessions as $session)
        @php
          $num_remaining = $session->registration_limit - $registrations[$session->id]->num_registrations;

          if (!(isset($admin) && $admin) && $num_remaining < 0) {
            $num_remaining = 0;
          }
        @endphp
        <div class="list-group-item">
          <dates>
            <start-date>{{ $session->start_date->format("F jS") }}</start-date>
            &ndash;
            <end-date>{{ $session->end_date->format("jS") }}</end-date>
          </dates>
          <attendance-cap> {{ $num_remaining }} / {{ $session->registration_limit }}</attendance-cap>
          <buttons>
            <label>
              <input type="radio" name="orientation_session" value="{{ $session->id }}"
                @if($num_remaining <= 0 && !(isset($admin) && $admin)) disabled @endif
                @if(isset($registration) && $registration->fkey_sims_session_id == $session->id) checked @endif
                class="orientation_session" required/>
              I want to attend this session
            </label>
          </buttons>
        </div>
      @endforeach
    </div>
  </div>
</div>
