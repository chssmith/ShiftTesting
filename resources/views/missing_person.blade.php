@extends('forms_template')

@section('javascript')
@endsection

@section('stylesheets')
	@parent
@endsection

@section("content")
	<form 	id = "AddressForm" method = "POST"
     	  	action = "{{ action('StudentInformationController@missingPersonContactUpdate') }}">
		{{ csrf_field() }}

		<div class="row">
			<div class="col-xs-12">
				<div class="panel">
					<div class="panel-body">		
							In compliance with the <strong> Federal Clergy Act </strong>, we ask that you provice a missing person contact. This may the same contact information as an emergency contact provided on the previous screen. <strong> In case you are missing, who should we contact? </strong>
					</div>
				</div>
			</div>
		</div>

		@include('partials.contact_info')
    </form>
@endsection	