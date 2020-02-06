@extends('forms_template')

@section('heading')
  SIMS Registration
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
  <div class="row">
    <div class="col-md-12">
      @include("sims.partials.complete")
    </div>
  </div>
@endsection
