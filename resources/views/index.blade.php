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
		<link type="text/css" rel="stylesheet" href="{{ asset("css/global.css") }}" />
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

			.complete {
				background-color: #70A204;
			}
			.incomplete {
				background-color: #CB0D0B;
			}
			.btn-complete {
				color: #FFFFFF;
				text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
				background-color: #649103;
				*background-color: #649103;
				background-image: -moz-linear-gradient(top, #70A204, #649103);
				background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#70A204), to(#649103));
				background-image: -webkit-linear-gradient(top, #70A204, #649103);
				background-image: -o-linear-gradient(top, #70A204, #649103);
				background-image: linear-gradient(to bottom, #70A204, #649103);
				background-repeat: repeat-x;
				border-color: #649103;
				border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
				filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#70A204', endColorstr='#649103', GradientType=0);
				filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
			}
			.btn-complete:hover,
			.btn-complete:focus,
			.btn-complete:active,
			.btn-complete.active,
			.btn-complete.disabled,
			.btn-complete[disabled] {
				color: #FFFFFF;
				background-color: #649103;
				*background-color: #003bb3;
			}

		</style>
@endsection

@section("content")
	<div class="row">
		<div class="col-md-8 col-md-offset-2 col-xs-12">
			<div class="panel">
				<div class="panel-body">
					<h3 style="margin-top; 0px">Welcome	to the RC Community!</h3>

					<p>
						<ul style="list-style: circle; margin-bottom: 10px; padding-left: 40px;">
							<li>
								You must go through each section.
							</li>
							<li>
								Progress is saves as you complete each section.
							</li>
							<li>
								Make sure you hit the submit button when you are finished.  Make
								sure you recieve a confirmation email.
							</li>
						</ul>

						We appreciate your time and look forward to seeing you on campus.
					</p>
					<p>
						Go Maroons!
					</p>
				</div>
			</div>
		</div>
	</div>


	<div id="accordion" class="panel panel-default">
 		<div class="panel-heading" role="tab" id="headingForms">
    	<h4 class="panel-title">
		    <a role="button" target="Forms" class="accordian-button" style="color:black;" data-toggle="collapse" data-parent="#accordion" href="#collapseForms" aria-expanded="true" aria-controls="collapseForms">
		    	<div>
			        Student Information Forms
			        @if(false)
		        		<span style="background-color:#70A204" class="badge">
		        			Completed
								</span>
		        	@elseif(false)
		        		<span style="background-color:#FCBF06; color:black" class="badge">
		        			Submitted
								</span>
		        	@else
		        		<span style="background-color:#CB0D0B" class="badge">
		        			Incomplete
								</span>
		        	@endif
			    	<span id="Forms" class="accordian-circle fas fa-chevron-circle-up fa-lg pull-right circle" style="color: grey"></span>
	        </div>
		    </a>
			</h4>
		</div>

		<div id="collapseForms" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingForms">
	   	<div class="panel-body">
	   		<div class="panel panel-default">
			    <div class="list-group">
			    	@foreach($sections as $section_name => $section_completion)
		        	<a href="{{$section_completion['link']}}" class="list-group-item">
								{{$section_name}}
								<span class="badge pull-right @if(is_null($section_completion['status'])) not-started @elseif($section_completion['status']) complete @else incomplete @endif">
									@if (is_null($section_completion['status']))
										Not Started
									@elseif($section_completion['status'])
										<span class="fas fa-check" aria-hidden="true"></span>
									@else
										Incomplete
									@endif
								</span>
		        	</a>
		        @endforeach
			    </div>
				</div>
				<div style="text-align: right; margin: 20px 0px;">
					<a class="btn btn-complete btn-lg" href="{{action('StudentInformationController@confirmation')}}" class="list-group-item"> Submit</a>
				</div>
			</div>
		</div>

 		<div class="panel-heading" role="tab" id="headingOthers" style="border-top: solid 1px #70132D">
    	<h4 class="panel-title">
		    <a role="button" class="accordian-button" target="OtherStuff" style="color:black" data-toggle="collapse" data-parent="#accordion" href="#collapseOthers" aria-expanded="true" aria-controls="collapseOthers">
					<div>
						<span id="OtherStuff" class="accordian-circle fas fa-chevron-circle-up fa-lg pull-right check" style="color: grey"></span>
						Other Forms
					</div>
		    </a>
  		</h4>
  	</div>

		<div id="collapseOthers" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOthers">
	   	<div class="panel-body">
				<div class="panel panel-default">
					@include("partials.other_forms")
				</div>
	   	</div>
	  </div>
  </div>
@endsection
