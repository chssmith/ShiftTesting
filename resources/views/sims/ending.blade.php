@extends('forms_template')

@section('heading')
  Summer Orientation Registration
@endsection

@section("header")
@endsection

@section('javascript')
  <script>

  </script>
@endsection

@section("stylesheets")
  @parent
	<link type="text/css" rel="stylesheet" href="{{ asset("css/global.css") }}" />
@endsection

@section("content")
  @if($err == 1)
    <div class="row">
      <div class="col-md-12">
        <div style="margin-bottom:15px" id="warning" class="alert alert-warning light no-margin">
          <h3 style="margin-top: 5px"> Sorry! </h3>
          <p>You have already registered for summer orientation.  If any information is incorrect, please email <a href="mailto:orientation@roanoke.edu">orientation@roanoke.edu</a>.</p>
        </div>
      </div>
    </div>
  @endif
  <div class="row">
    <div class="col-md-12">
      @include("sims.partials.complete")
    </div>
  </div>
@endsection
