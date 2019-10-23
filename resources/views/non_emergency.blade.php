@extends('forms_template')

@section('javascript')
@endsection

@section('heading')
	Non-Emergency Contact
@endsection

@section("stylesheets")
	@parent
	<link type="text/css" rel="stylesheet" href="{{ asset("css/global.css") }}" />

	<style>
		label{
			font-size:20px;
		}

		.panel-body {
			font-size: 24px;
			line-height: 18pt;
		}
	</style>
@endsection

@section("content")
	<form id="nonEmergencyInfo" method="POST"
		action="{{ action("StudentInformationController@nonEmergencyUpdate") }}">
		{{ csrf_field() }}

		<div class = "row">
			<div class="col-xs-12">
				<div class="panel">
					<div class="panel-body">
						<p>
							Occasionally, Roanoke College uses automated text messages to
							reach students for non-emergency purposes.
						</p>
						<p style="margin-bottom: 0px;">
							For example, reminder
							messages are sent about Health Services appointments, intramurals,
							and course evaluations.
						</p>
					</div>
				</div>
     		<div>
					<div class="pretty p-default">
						<input type="checkbox" name="non_emergency" value="non_emergency" id="non_emergency" @if($student->non_emergency || is_null($student->non_emergency)) checked @endif />
							<div class="state p-primary">
								<label>I wish to recieve these automated messaged</label>
							</div>
					</div>
				</div>
			</div>
		</div>

		 <div class = "row">
			 <div class="col-xs-12">
				 <div class="btn-toolbar">
					 <button type = "submit" class = "btn btn-lg btn-success pull-right"> Save and Continue </button>
					 <a href="{{action('StudentInformationController@index')}}" class="btn btn-lg btn-danger pull-right"> Cancel </a>
				 </div>
			 </div>
		 </div>
	 </form>
@endsection
