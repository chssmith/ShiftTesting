@extends('forms_template')

@section('javascript')
@endsection

@section('heading')
	Independent Student
@endsection

@section("stylesheets")
	@parent

	<style>
		label{
			font-size:20px;
		}

		ul{
			list-style-type:disc;
			padding-left:25px;
		}
	</style>
@endsection

@section("content")
	<form 	id = "independentStudent" method = "POST"
     	  	action = "{{ action("StudentInformationController@independentStudentUpdate") }}">
		{{ csrf_field() }}

		<div class = "row">
   	     	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="panel">
					<div class="panel-body">						
						<strong> In order to be considered an independent student, at least one of the following statements must be true: </strong>
						<ul> 
							<li>
								You are at least 24 years old on January 1st of this year.
							</li>

							<li>
								You will be working on a degree beyond a bachelor's degree, such as a master's or doctorate, in the current school year.
							</li>

							<li>
								You are married or you are seperated and not yet divorced
							</li>

							<li>	
								You have children who recieve more than half of their support from you OR you have dependents (other than your children or spouse) that live with you and recieve more than half of their support from you.
							</li>	

							<li>
								You are an orphan or ward of the court or were a ward of the court until age 18
							</li>

							<li>
								You are a veteran of the U.S. Armed Forces
							</li>

							<li>
								You are currently serving on active duty in the Armed Forces for other than training purposes.
							</li>

							<li>
								You are currently homeless
							</li>
						</ul>
					</div>
				</div>

   	     		<div>
     				<div class="pretty p-default">
	            		<input type = "checkbox" name = "independent_student" value = "independent_student" id = "independent_student" @if($student->independent_student) checked @endif />
	            		<div class="state p-primary">
	            			<label>I am an Independent Student.</label>
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
	