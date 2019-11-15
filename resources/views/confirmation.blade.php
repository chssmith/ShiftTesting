@extends('forms_template')

@section('heading')
	Confirmation
@endsection

@section('javascript')

@endsection



@section("content")
	<h3> Thank You! </h3>

	<p>
		Your Student Information forms have been completed. If you would like to
		make any changes to the information supplied, please do so now by hitting
		the <i>"Back"</i> button.
	</p>

	<p>
		Otherwise, click "Submit" below to submit
		your information. A confirmation message will be sent to your
		mail.roanoke.edu email address.
	</p>

	<div class = "row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="btn-toolbar">
				<form method="POST"
							action="{{ action("StudentInformationController@confirmationUpdate") }}">
					{{ csrf_field() }}

		    	<button type = "submit" class = "btn btn-lg btn-success pull-right"> Submit </button>
				</form>
	  		<a href="{{action('StudentInformationController@index')}}" class="btn btn-lg btn-danger pull-right"> Back </a>
			</div>
		</div>
	</div>
@endsection
