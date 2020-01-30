@extends('forms_template')

@section('heading')
  Summer Orientation Registration
@endsection

@section("header")
@endsection

@section('javascript')

@endsection

@section("stylesheets")
  @parent
	<link type="text/css" rel="stylesheet" href="{{ asset("css/global.css") }}" />
  <style>
    .panel-body p {
      line-height: 1.3;
      padding: 10px;
    }
  </style>
@endsection

@section("content")
  @if ($messages->count() > 0)
    <div style="margin-bottom:15px" id="warning" class="alert alert-warning light no-margin">
      <h3 style="margin-top: 5px"> Sorry! </h3>
      @foreach($messages as $message)
        <p> {!! $message !!} </p>
      @endforeach
    </div>
  @endif

  <div class="panel">
    <div class="panel-body">
      @include("sims.stage1.partials.confirmation_body")
    </div>
  </div>
@endsection
