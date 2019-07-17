@extends('forms_template')

@section("header")
  <link media="all" type="text/css" rel="stylesheet" href="//redstone.roanoke.edu/shared/template/public/assets/stylesheets/bootstrap.css">
  <link media="all" type="text/css" rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
@endsection

@section('javascript')
	
@endsection

@section("content")
			<div class="panel">
				<div class="panel-body">
					Welcome	to the RC Community!
					<br><br>
					We need your help with completing some required forms, which will take about 15 minutes. Sorry for all the questions but we need your input! When you have successfully submitted the Student Information Form, you will receive an RC email that confirms this. If you don't get an e-mail confirmation, you've missed a step(s) and need to go back through the form. Carefully completing all items on the form is a good thing so you aren't face with a do-over! We appreciate your time and look forward to see you on campus.

					Go Maroons!
				</div>
			</div>

	<div class="panel panel-default">
	    <div class="list-group">
	    	@foreach($sections as $section_name => $section)
	        	<a href="{{$section['link']}}" class="list-group-item"> {{$section_name}}        
		        	@if($section['status'] == "Complete")
		        		<span style="background-color:#70A204" class="badge">  
		        			Completed
						</span>
		        	@elseif($section['status'] == "Pending")
		        		<span style="background-color:#FCBF06; color:black" class="badge">  
		        			Pending
						</span>
		        	@elseif($section['status'] == "WIP")
		        		<span style="background-color:#grey; color:black" class="badge">  
		        			Work In Progress
						</span>		        		
		        	@else
		        		<span style="background-color:#CB0D0B" class="badge">  
		        			Incomplete
						</span>
		        	@endif
	        	</a>
	        @endforeach
	    </div>
	</div>
@endsection