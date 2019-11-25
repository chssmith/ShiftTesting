@extends('forms_template')

@section('heading')
  SIMS Registration
@endsection

@section("header")
@endsection

@section('javascript')

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
          <abutton type="button" class="btn btn-complete" style="width: 100%">Register!</button>
        </buttons>
      </div>
    @endforeach
  </div>
@endsection
