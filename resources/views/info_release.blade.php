@extends('forms_template')

@section('javascript')
@endsection

@section("stylesheets")
	@parent

	<style>
		label{
			font-size:17px;
		}

		.pretty {
		    white-space: inherit;
		    width: 100%;
		}

		.pretty .state label{
		      text-indent: 0;
		      padding-left: 30px;
		}

		.pretty .state label:after, 
		.pretty .state label:before{
		     top: 0;
		}	
</style>
@endsection

@section("content")
	<form 	id = "independentStudent" method = "POST"
     	  	action = "{{ action("StudentInformationController@infoReleaseUpdate", ['id'=>$guardian->id]) }}">
		{{ csrf_field() }}

		<div class = "row">
   	     	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="panel">
					<div class="panel-body">						
						<strong> 
							In accordance with the Family Educational Rights and Privacy Act,
							Roanoke College will only diclose information from a student's education record,
							(other than directory information)
							with the prior written consent of the student,
							unless authorized below.
						</strong>
					</div>
				</div>
				<h3> {{ $guardian->first_name. ' ' .$guardian->last_name }} </h3>
   	     		<div>
   	     			<div>
	     				<div class="pretty p-default p-round">
		            		<input type = "radio" name = "info_release" value = "info_release" id = "info_release" @if($student->info_release) checked @endif />
		            		<div class="state p-primary">
		            			<label>I authorize Roanoke College, including the office of the Registrar and other appriopriate Academic Affairs faculty and staff,
		            			permission to release information from my education record, including grades,
		            			and to discuss my academic proogress and matters related to my enrollment with my parent/guardian listed above.
		            			Further, I authorize Roanoke College to provide on-line access to the parent/guardian named above.
		            			Access will include billing and finacial aid, as well as academic information (grades and scheduling).
		            			This will NOT include access to my Roanoke College e-mail account.</label>
		            		</div>
		            	</div>
	            	</div>
	            	<div>
		            	<div class="pretty p-default p-round">
		            		<input type = "radio" name = "info_release" value = "" id = "disagree" @if(!$student->info_release) checked @endif />
		            		<div class="state p-primary">
		            			<label>I DO NOT authorize Roanoke College to release information from my education record to my parent/guardian listed above.
		            			</label>
		            		</div>
		            	</div>
	            	</div>
	            </div>
	        </div>
	    </div>
	            

   	    <div class = "row">
        	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    		    <button type = "submit" class = "btn btn-lg btn-success pull-right"> Submit </button>
        	</div>
        </div>
    </form>
@endsection