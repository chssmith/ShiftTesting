@extends('template')

@section("stylesheets")
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css"/>
	<link rel="stylesheet" type="text/css" href="{{ asset("css/global.css") }}" />
	<style>
		.pretty{
			 margin-bottom:10px;
		}
	</style>
@endsection

@section("content")
	<div class="row">
		<div class="col-md-12 col-lg-10 col-lg-offset-1">
			@yield("content")
		</div>
	</div>
@overwrite
