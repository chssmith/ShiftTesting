@extends('forms_template')

@section('heading')
	Missing Person Information
@endsection

@section('javascript')
	<script type="text/javascript" src="{{ URL::asset('js/contact.js') }}"></script>
@endsection

@section('stylesheets')
	@parent
	<link rel="stylesheet" href="{{ URL::asset('css/contact.css') }}" />
	<link rel="stylesheet" href="{{ asset("css/global.css") }}" />
@endsection

@section("content")

	<div style="margin-bottom:15px" id="warning" class="alert alert-danger no-margin" hidden>

	</div>
	<form id="AddressForm" method="POST"
		action="{{ action('StudentInformationController@missingPersonContactUpdate') }}">
		{{ csrf_field() }}

		<div class="row">
			<div class="col-xs-12">
				<div class="panel">
					<div class="panel-body">
						In compliance with the <strong>Federal Clery Act</strong>, we ask that you provide a missing person contact. <strong>In case you are missing, who should we contact?</strong>
					</div>
				</div>
			</div>
		</div>

		@include('partials.contact_info')

		<div class="pull-right">
			<div class="pretty p-default">
	   		<input type="checkbox" name="emergency" value="emergency" id="emergency" @if(!empty($contact) && $contact->emergency_contact) checked @endif />
	   		<div class="state p-primary">
	   			<label>This is also an Emergency Contact</label>
	   		</div>
	   	</div>
	  </div>

		<div class = "row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="btn-toolbar">
					<button type = "submit" class = "btn btn-lg btn-success pull-right"> Save and Continue </button>
					<a href="{{action('StudentInformationController@index')}}" class="btn btn-lg btn-danger pull-right"> Cancel </a>
				</div>
			</div>
		</div>
	</form>
@endsection
