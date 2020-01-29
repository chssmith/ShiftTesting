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
	<div id="grid-container">
		<div id="top">
			<div class="row">
				<div class="col-md-12 col-lg-10 col-lg-offset-1">
					@yield("content")
				</div>
			</div>
		</div>
		<div id="bottom">
			<div class="row">
				<div class="col-lg-10 col-lg-offset-1 col-md-12 note">
					<span class="far fa-asterisk" aria-hidden="true"></span> Fields with this symbol are required for submitting the overall form.  Most sections will allow you to save with data missing in these fields.
				</div>
			</div>
		</div>
	</div>
@overwrite
