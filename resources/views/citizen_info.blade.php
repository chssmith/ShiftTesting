@extends('forms_template')

@section('heading')
	Citizenship Information
@endsection

@section('javascript')
	<script>
		function hideShow(show, target_id){
			var div_to_hideshow = document.getElementById(target_id + '_span');
			$(div_to_hideshow).toggle();
		}

		$('.hideshowbox').on('click', function(){
			hideShow(this.checked, this.id);
		});

		$('#states').on('change', function(){
			if(this.value == "VA"){
				$('#VA_span').show();
			}else{
				$('#VA_span').hide();
			}
		});

		$('.foreignCard[value="Visa"]').on('change', function(){
			$("#Visa_span").toggle();
		});
	</script>
@endsection

@section("stylesheets")
	@parent
	<link type="text/css" rel="stylesheet" href="{{ asset("css/global.css") }}" />

@endsection

@section("content")
	<form id="CitizenForm" method="POST"
				action="{{ action("StudentInformationController@citizenInfoUpdate") }}">
		{{ csrf_field() }}

		<h3> Citizenship Information </h3>


		<div class="row">
			<div class="col-sm-12 col-md-6">
				<div class="form-group">
					<label for="BirthCountry">
						 Country of birth
					</label>
					<select name="BirthCountry" form="CitizenForm" class="form-control" id='BirthCountry'>
						<option></option>
						@foreach($countries as $country)
							<option @if(!empty($citizenship) && $citizenship->country_of_birth == $country->key_CountryId) selected @endif value="{{$country->key_CountryId}}">
								{{ $country->CountryName }}
							</option>
						@endforeach
					</select>
				</div>
			</div>
		</div>

		<hr />

		<div class="row">
			<div class="col-xs-12">
				<h3 style="margin-bottom: 20px"> Check all that apply </h3>

				<div>
					<div class="pretty p-default">
		    		<input type="checkbox" class="hideshowbox" name="US_citizen" value=true id="US_citizen" @if($citizenship->us) checked @endif />
		    		<div class="state p-primary">
		    			<label>I am a United States Citizen</label>
		    		</div>
		    	</div>
		    </div>

		    <div id="US_citizen_span" @if(!$citizenship->us) hidden @endif>
		    	<div class="row">
		      	<div class="col-xs-12 col-sm-6 form-group">
							<label for="states">
			      		State of Residence
							</label>
				    	<select name="state" form="CitizenForm" class="form-control" id='states'>
		 						<option></option>
					    	@foreach($states as $state)
		  						<option value="{{$state->StateCode}}" @if(!empty($us_resident) && $us_resident->fkey_StateCode == $state->StateCode) selected @endif>
										{{ $state->StateName }}
									</option>
		  					@endforeach
				    	</select>
		        </div>
					</div>
			    <div id="VA_span" @if(empty($us_resident) || $us_resident->fkey_StateCode != "VA") hidden @endif>
			      <div class="row">
			      	<div class="col-xs-12 col-md-6 form-group">
								<label for="counties">
			        		City/County of Residence
								</label>
						    <select name="county" form="CitizenForm" class="form-control" id='counties'>
									<option></option>
						    	@foreach($counties as $id => $county)
										<option value="{{ $id }}" @if(!empty($us_resident) && $us_resident->fkey_CityCode == $id) selected @endif>
											{{ $county->display }}
										</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>
				</div>

		    <div>
					<div class="pretty p-default">
		    		<input type="checkbox" class="hideshowbox" name="another_citizen" value=true id="other_citizen" @if($citizenship->another) checked @endif />
		    		<div class="state p-primary">
		    			<label>I am a citizen of another country.</label>
		    		</div>
		    	</div>
		    </div>

		    <div class="row" id="other_citizen_span" @if(!$citizenship->another) hidden @endif>
					<div class="row">
						<div class="col-xs-12 col-md-6">
							<div class="form-group">
						    <label for="PermanentCountry">
						      Country of permanent residence or domicile
						    </label>
						    <select name="PermanentCountry" form="CitizenForm" class="form-control" id='PermanentCountry'>
						      <option></option>
						      @foreach($countries as $country)
						        <option @if($citizenship->permanent_residence == $country->key_CountryId) selected @endif
						          value="{{$country->key_CountryId}}">
						          {{ $country->CountryName }}
						        </option>
						      @endforeach
						    </select>
						  </div>
						</div>
					</div>

		     	@php($foreign_count = 1)
		    	@foreach($citizenship->countries as $individual_country)
			     	@include("partials.citizenship_info")
				    @php($foreign_count++)
			    @endforeach

			    @php($individual_country = NULL)

			    @if($foreign_count < 3)
			    	@for($foreign_count = $foreign_count; $foreign_count < 3; $foreign_count++)
				     	@include("partials.citizenship_info")
				    @endfor
					@endif

					<div class="col-md-12">
		        <div>
							<h3> I am in the U.S on</h3>
						</div>
		        <div>
							<div class="pretty p-default p-round">
				    		<input type="checkbox" name="GreenCard[]" class="foreignCard" value="GreenCard" id="GreenCard" @if($citizenship->green_card) checked @endif />
				    		<div class="state p-primary p-round">
				    			<label>Permanent Residency</label>
				    		</div>
				    	</div>

				    	<div class="pretty p-default p-round">
				    		<input type="checkbox" class="foreignCard" name="GreenCard[]" value="Visa" id="Visa" @if(!empty($visa)) checked @endif />
				    		<div class="state p-primary p-round">
				    			<label>Visa</label>
				    		</div>
				    	</div>

				    	<div class="row" id="Visa_span" @if(empty($visa)) hidden @endif>
			        	<div style="padding-left:0; margin-bottom: 20px;" class="col-xs-12 col-md-6">
									<label for="visa_type">
					    			Visa Type
									</label>
							    <select name="VisaTypes" form="CitizenForm" class="form-control" id='visa_type'>
						    		@foreach($visa_types as $visa_type)
			  							<option value="{{ $visa_type->code }}" @if(!empty($visa) && $visa->fkey_code == $visa_type->code) selected @endif>
												{{ $visa_type->descr }}
											</option>
				  					@endforeach
							    </select>
								</div>
							</div>
				    </div>
					</div>
		    </div>
				<div>
					<div class="pretty p-default">
						<input type="checkbox" class="hideshowbox" name="other_citizen" value=true id="other" @if($citizenship->other) checked @endif/>
						<div class="state p-primary">
							<label>Other</label>
						</div>
					</div>
				</div>

		    <div class = "row">
		    	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		    		<div class="btn-toolbar">
		  		    <button type="submit" class="btn btn-lg btn-success pull-right"> Save and Continue </button>
		      		<a href="{{action('StudentInformationController@index')}}" class="btn btn-lg btn-danger pull-right"> Cancel </a>
		    		</div>
		    	</div>
		    </div>
			</form>
		</div>
	</div>
@endsection
