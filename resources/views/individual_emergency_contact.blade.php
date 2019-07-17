@extends('forms_template')

@section('javascript')
@endsection

@section('stylesheets')
	@parent
@endsection

@section("content")
	<form 	id = "emergencyForm" method = "POST"
     	  	action = "{{ action('StudentInformationController@emergencyContactUpdate', ['id'=>$id]) }}">
		{{ csrf_field() }}
		<h3> New Emergency Contact </h3>
		@include('partials.contact_info')
    </form>
@endsection	