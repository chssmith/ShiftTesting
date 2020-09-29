@extends('forms_template')

@section('heading')
	Admin
@endsection

@section('javascript')
@endsection

@section('stylesheets')
	<link type="text/css" rel="stylesheet" href="{{ asset("css/global.css") }}" />
  <style>
    .missing {
      padding-bottom: 20px;
      padding-left: 40px;
    }
    .missing ul {
      padding-left: 40px;
      list-style-type: disc;
      font-size: 14pt;
      line-height: 20pt;
    }
  </style>
@endsection

@section("content")
  <a href="{{ action ("AdminController@index") }}" class="btn btn-primary"><span class="far fa-arrow-left"></span> Back</a>
  <div style="background-color: white; border: solid 1px var(--dark-grey); margin: 20px 0px; padding: 20px;">
    <h2>Missing Data Report - {{ $student->display_name }}</h2>
    <div class="missing">
      {!! $messages !!}
    </div>
  </div>
@endsection
