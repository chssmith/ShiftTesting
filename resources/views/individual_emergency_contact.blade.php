@extends('forms_template')

@section('javascript')
	<script type="text/javascript" src="{{ URL::asset('js/contact.js') }}"></script>
@endsection

@section('stylesheets')
	@parent
	<link rel="stylesheet" href="{{ URL::asset('css/contact.css') }}" />
@endsection 

@section("content")
	<div style="margin-bottom:15px" id="warning" class="alert alert-danger no-margin" hidden>
		
	</div>

	<form 	id = "emergencyForm" method = "POST"
     	  	action = "{{ action('StudentInformationController@emergencyContactUpdate', ['id'=>$id]) }}">
		{{ csrf_field() }}
		<h3> Emergency Contact </h3>
		@include('partials.contact_info')

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