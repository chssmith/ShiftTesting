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

  <div class="modal fade" id="register_modal" tabindex="-1" role="dialog" aria-labelledby="register_modal_title">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="register_modal_title">Register for SIMS</h4>
        </div>
        <form action="" method="POST">
          {{ csrf_field() }}
          <div class="modal-body">
            <div class="form-group">
              <label for="guardian">
                <input type="checkbox" id="guardian" name="guardian_stay" />
                My guardian would like to stay on campus
              </label>
            </div>
            <div id="guardian_info">
              <div class="form-group">
                <label for="guardian_name">Guardian Name</label>
                <input type="text" class="form-control" id="guardian_name" name="guardian_name" />
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

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
          <abutton type="button" class="btn btn-complete" style="width: 100%" data-target="#register_modal" data-toggle="modal" data-sim-id="{{ $session->id }}">Register!</button>
        </buttons>
      </div>
    @endforeach
  </div>
@endsection
