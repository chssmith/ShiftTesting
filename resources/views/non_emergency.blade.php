@extends('forms_template')

@section('javascript')
@endsection

@section('heading')
	Non-Emergency Contact
@endsection

@section("stylesheets")
	@parent

	<style>
		label{
			font-size:20px;
		}
	</style>
@endsection

@section("content")
	<form 	id = "nonEmergencyInfo" method = "POST"
     	  	action = "{{ action("StudentInformationController@nonEmergencyUpdate") }}">
		{{ csrf_field() }}

		<div class = "row">
   	     	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="panel">
					<div class="panel-body">
						<strong> Occasionally, Roanoke College uses automated text to reach students for non-emergency purposes. For Example, occasional reminder messages are sent about Health Services appointments, intramurals, and course evaluations. </strong>
					</div>
				</div>

   	     		<div>
     				<div class="pretty p-default">
	            		<input type = "checkbox" name = "non_emergency" value = "non_emergency" id = "non_emergency" @if($student->non_emergency || is_null($student->non_emergency)) checked @endif />
	            		<div class="state p-primary">
	            			<label>I wish to recieve these automated messaged</label>
	            		</div>
	            	</div>
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