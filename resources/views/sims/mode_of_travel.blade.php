@extends('forms_template')

@section('heading')
  SIMS Registration
@endsection

@section("header")
@endsection

@section('javascript')
  <script>
    $(document).ready(function(){
      @if(count($sess) > 0)
        $("#mot").val("{{$sess["mode_of_travel"]}}");
        @if($sess["shuttle"] == "yes")
          $(".shuttle").prop("checked", true);
        @else
          $("#shuttle").prop("checked", true);
        @endif
      @endif
    });
  </script>
@endsection

@section("stylesheets")
  @parent
	<link type="text/css" rel="stylesheet" href="{{ asset("css/global.css") }}" />
@endsection

@section("content")
  @include("sims.partials.tabs")
  <h2> Your Mode of Travel </h2>
  <form action="{{action("SIMSRegistrationController@modeOfTravel")}}" method="POST">
    {{csrf_field()}}
    <div class="form-group">
      <div class="row">
        <div class="col-md-12">
          <label name="mode_of_travel">How will you be traveling to Roanoke?</label>
          <select name="mode_of_travel" class="form-control" id="mot" required>
            <option value="" selected hidden disabled>-Please Select a Mode of Travel-</option>
            @foreach($MOT as $mode)
              <option value="{{$mode->id}}">{{$mode->travel_type}}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>
    <br>
    <div class="form-group">
      <div class="row">
        <div class="col-md-12">
          <p>Transportation can be provided to and from the Roanoke Regional Airport or the Roanoke Amtrak Station.  Students and families can be picked up as early as 8am on the morning of Day One and can be dropped off at the station after 5pm on Day Two.  Please book travel accordingly; it may be difficult to accommodate outside of this time frame.  If you choose yes, please send your travel details to orientation@roanoke.edu, as soon as possible.</p>
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-md-12">
          <label name="shuttle">Will you be using the shuttle service?</label><br>
          <label><input type="radio" name="shuttle" value="yes" class="shuttle" required />&nbsp;Yes</label>&nbsp;
          <label><input type="radio" name="shuttle" value="no" id="shuttle" />&nbsp;No</label>
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-md-12">
          <button type="submit" class="btn btn-info btn-md pull-right">Next <span class="fas fa-arrow-right"></span></button>
        </div>
      </div>
    </div>
  </form>
@endsection
