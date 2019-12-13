@extends('forms_template')

@section('heading')
	Student Forms
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

			.modal-body {
				font-family: "Calibri";
			}
			.missing_info {
				line-height: 1.8;
				font-weight: bold;
			}

			#additional_forms a.list-group-item {
				display: grid;
				grid-template-areas: "title due-date badge";
				grid-template-columns: 4fr 200px 95px;
				grid-column-gap: 20px;
			}

			#additional_forms link-title {
				grid-area: title;
			}
			#additional_forms due-date {
				grid-area: due-date;
			}
			#additional_forms badge {
				grid-area: badge;
			}

			@media (max-width: 1200px) {
				#additional_forms a.list-group-item {
					grid-template-areas: "title title" "due-date badge";
					grid-template-columns: 1fr 1fr;
					grid-row-gap: 30px;
				}
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
								Progress is saved as you complete each section.
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

	@if(\Session::has("message"))
		<div class="row">
			<div class="col-xs-12">
				<div class="alert alert-warning light">
					{{ \Session::get("message") }}
				</div>
			</div>
		</div>
	@endif

	<div id="accordion" class="panel panel-default">
 		<div class="panel-heading" role="tab" id="headingForms">
    	<h4 class="panel-title">
		    <a role="button" target="Forms" class="accordian-button" style="color:black;" data-toggle="collapse" data-parent="#accordion" href="#collapseForms" aria-expanded="false" aria-controls="collapseForms">
		    	<div>
			        Student Information Forms
							@if($submitted)
		        		<span style="background-color:#FCBF06; color:black" class="badge">
		        			Submitted
								</span>
		        	@else
		        		<span style="background-color:#CB0D0B" class="badge">
		        			Incomplete
								</span>
		        	@endif
			    	<span id="Forms" class="accordian-circle fas fa-chevron-circle-down fa-lg pull-right circle" style="color: grey"></span>
	        </div>
		    </a>
			</h4>
		</div>

		<div id="collapseForms" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingForms">
	   	<div class="panel-body">
	   		<div class="panel panel-default">
			    <div class="list-group">
			    	@foreach($sections as $section_name => $section_completion)
		        	<a @if(!$submitted)href="{{$section_completion['link']}}"@endif class="list-group-item">
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
					<button class="btn btn-complete btn-lg" data-toggle="modal" data-target="#confirm_submit" @if($submitted) disabled @endif>Submit</a>
				</div>
			</div>
		</div>

		<div class="modal fade" id="confirm_submit" tabindex="-1" role="dialog" aria-labelledby="confirm_submit_title">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title" id="confirm_submit_title">Confirm Submit</h4>
		      </div>
		      <div class="modal-body" style="font-size: 12pt;">
		        <p>
							Please make sure all information for the student information forms is correct before submitting.
						</p>

						<p>
							Once you submit these forms, you will not be able to make changes until after the form is fully processed.
						</p>
						@php
							$missing_info = array_reduce($sections, function ($counter, $item) {if ($item['status'] == '0') {$counter++;} return $counter;}, 0) > 0;
						@endphp
						@if ($missing_info)
							<div class="alert alert-danger light missing_info">
								There is some missing information:
								<div class="list-group">
									@foreach($sections as $section_name => $section)
										@if($section['status'] == '0')
											<a href="{{ $section['link'] }}" class="list-group-item">
												{{$section_name}}
											</a>
										@endif
									@endforeach
								</div>
							</div>
						@endif
		      </div>
		      <div class="modal-footer">
						<form action="@if(!$missing_info){{action('StudentInformationController@confirmationUpdate')}}@endif" method="POST">
							{{ csrf_field() }}
		        	<button type="submit" class="btn btn-complete" @if($missing_info) disabled @endif>Submit</button>
						</form>
		      </div>
		    </div>
		  </div>
		</div>

 		<div class="panel-heading" role="tab" id="headingOthers" style="border-top: solid 1px #70132D">
    	<h4 class="panel-title">
		    <a role="button" class="accordian-button" target="OtherStuff" style="color:black" data-toggle="collapse" data-parent="#accordion" href="#collapseOthers" aria-expanded="false" aria-controls="collapseOthers">
					<div>
						<span id="OtherStuff" class="accordian-circle fas fa-chevron-circle-down fa-lg pull-right check" style="color: grey"></span>
						Additional Forms
					</div>
		    </a>
  		</h4>
  	</div>

		<div id="collapseOthers" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOthers">
	   	<div class="panel-body">
				<div class="panel panel-default">
					@include("partials.other_forms")
				</div>
	   	</div>
	  </div>
  </div>
@endsection
