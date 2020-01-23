@extends('forms_template')

@section('heading')
  SIMS Registration
@endsection

@section("header")
@endsection

@section('javascript')
  <script>
    $(document).on("click", ".registerBTN", function(){
      const id = $(this).attr("data-sim-id");
      $.ajax({
        url: "{{action("SIMSRegistrationController@sessionSelection")}}",
        type: "POST",
        data: {
          _token: "{{csrf_token()}}",
          id: id
        },
        success: function(){
          window.location.href = "{{action("SIMSRegistrationController@studentInfoPage")}}"
        }
      })
    });
  </script>
@endsection

@section("stylesheets")
  @parent
	<link type="text/css" rel="stylesheet" href="{{ asset("css/global.css") }}" />
  <style>
    #SIMS {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      grid-gap: 20px;
    }
    .list-group-item {
      display: grid;
      grid-template-areas: "dates attendance-cap" "buttons buttons";
      grid-template-columns: 1fr 100px;
      grid-gap: 20px;
    }
    attendance-cap {
      justify-self: end;
    }
    dates {
      font-size: 18pt;
      line-height: 1.2em;
    }
    buttons {
      grid-area: buttons;
    }
    @media (max-width: 1919px) {
      #SIMS {
        grid-template-columns: 1fr 1fr;
      }
    }
    @media (max-width: 787px) {
      #SIMS {
        grid-template-columns: 1fr;
      }
    }
  </style>
@endsection

@section("content")
  <div class="progress" style="height: 4px;">
    <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
  </div>
  <h2> Available Sessions </h2>

  <div id="SIMS" class="list-group">
    @foreach($sessions as $session)
      <div class="list-group-item">
        <dates>
          <start-date>{{ $session->start_date->format("F jS") }}</start-date>
          &ndash;
          <end-date>{{ $session->end_date->format("F jS Y") }}</end-date>
        </dates>
        <attendance-cap>100 / {{ $session->registration_limit }}</attendance-cap>
        <buttons>
          <button type="button" class="btn btn-complete registerBTN" style="width: 100%" data-target="#register_modal" data-toggle="modal" data-sim-id="{{ $session->id }}">Register!</button>
        </buttons>
      </div>
    @endforeach
  </div>
@endsection
