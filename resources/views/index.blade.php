@extends('forms_template')

@section('heading')
	All Forms
@endsection

@section("header")
  <link media="all" type="text/css" rel="stylesheet" href="//redstone.roanoke.edu/shared/template/public/assets/stylesheets/bootstrap.css">
  <link media="all" type="text/css" rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
@endsection

@section('javascript')
	<script>
		$('.accordian-button').on('click', function(){
			var target = $('#' + this.target);
			if(target.hasClass('active')){
				var closed = true;
			}
			//$('.accordian-circle').removeClass('active');
			if(!closed){
				target.addClass('active');
			}else{
				target.removeClass('active');
			}
		})
	</script>
@endsection

@section("stylesheets")
	@parent
		<style>
			.accordian-circle.active {
				transform:          rotate(180deg);
				-ms-transform:      rotate(180deg);
				-moz-transform:     rotate(180deg);
				-webkit-transform:  rotate(180deg);
				-o-transform:       rotate(180deg);
			}

			.panel-title{
				width:100%;
			}
			.panel-heading {
				border-bottom: 10px solid;
			}
		</style>
@endsection

@section("content")
	<div class="row">
		<div class="col-md-8 col-md-offset-2 col-xs-12">
			<div class="panel">
				<div class="panel-body">
					Welcome	to the RC Community!
					<br><br>
					We need your help with completing some required forms, which will take about 15 minutes. Sorry for all the questions but we need your input! When you have successfully submitted the Student Information Form, you will receive an RC email that confirms this. If you don't get an e-mail confirmation, you've missed a step(s) and need to go back through the form. Carefully completing all items on the form is a good thing so you aren't face with a do-over! We appreciate your time and look forward to see you on campus.

					Go Maroons!
				</div>
			</div>



	<div id="accordion" class="panel panel-default">
 		<div class="panel-heading" role="tab" id="headingForms">
    		<h4 class="panel-title">
			    <a role="button" target="Forms" class="accordian-button" style="color:black;" data-toggle="collapse" data-parent="#accordion" href="#collapseForms" aria-expanded="false" aria-controls="collapseForms">
			    	<div>
				        Student Forms 
				        @if(false)
			        		<span style="background-color:#70A204" class="badge">  
			        			Completed
							</span>
			        	@elseif(false)
			        		<span style="background-color:#FCBF06; color:black" class="badge">  
			        			Pending
							</span>		        		
			        	@else
			        		<span style="background-color:#CB0D0B" class="badge">  
			        			Incomplete
							</span>
			        	@endif
				    	<span id="Forms" class="accordian-circle fas fa-chevron-circle-right fa-lg pull-right circle" style="color: grey"></span>
			        </div>
			    </a>
  			</h4>
  		</div>

		<div id="collapseForms" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingForms">
	    	<div class="panel-body">
	    		<div class="panel panel-default">
				    <div class="list-group">
				    	@foreach($sections as $section_name => $section_completion)
				        	<a href="{{$section_completion['link']}}" class="list-group-item"> {{$section_name}}      
				        	</a>  
				        @endforeach
				        	<a href="{{action('StudentInformationController@confirmation')}}" class="list-group-item"> Submit</a>
				    </div>
				</div>
	    	</div>
	   	</div>

 		<div class="panel-heading" role="tab" id="headingOthers">
    		<h4 class="panel-title">
			    <a role="button" class="accordian-button" target="OtherStuff" style="color:black" data-toggle="collapse" data-parent="#accordion" href="#collapseOthers" aria-expanded="false" aria-controls="collapseOthers">
			    	<div>
			    		<span id="OtherStuff" class="accordian-circle fas fa-chevron-circle-down fa-lg pull-right check" style="color: grey"></span>
					        Other Forms
				    </div>
			    </a>
  			</h4>
  		</div>

		<div id="collapseOthers" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOthers">
	    	<div class="panel-body">
	    		Other Forms can go here!
	    	</div>	
	   	</div>
    </div>
@endsection