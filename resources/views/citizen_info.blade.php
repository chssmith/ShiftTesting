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
	@php
		use \App\GenericCitizenship;
	@endphp
	<form id="CitizenForm" method="POST"
				action="{{ action("StudentForms\CitizenshipInformationController@store") }}">
		{{ csrf_field() }}

		<h3> Citizenship Information </h3>

		<div class="row">
			<div class="col-sm-12 col-md-6">
				<div class="form-group">
					<label for="BirthCountry">
						 Country of birth <span class="far fa-asterisk fa-xs fa-pull-right" aria-hidden="true"></span>
					</label>
					<select name="BirthCountry" form="CitizenForm" class="form-control" id='BirthCountry'>
						<option hidden></option>
						@foreach($countries as $country)
							<option
								@if(GenericCitizenship::matches_expected ($citizenship, "country_of_birth", $country->key_CountryId) ||
										(empty($citizenship->country_of_birth) && GenericCitizenship::matches_expected ($ods_citizenship, "country_of_birth", $country->key_CountryId)) ||
										(empty($citizenship->country_of_birth) && empty($ods_citizenship->country_of_birth) && $country->key_CountryId == '273'))
									selected
								@endif
								value="{{$country->key_CountryId}}">
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
		    		<input type="checkbox" class="hideshowbox" name="US_citizen" value=true id="US_citizen"
							@php
								$is_us_citizen = (!empty($citizenship) && $citizenship->us) || (empty($citizenship) && !empty($ods_citizenship) && ($ods_citizenship->us || !empty($ods_resident->fkey_StateCode) || !empty($ods_resident->fkey_CityCode)));
							@endphp
							@if ($is_us_citizen) checked @endif />
		    		<div class="state p-primary">
		    			<label>I am a United States Citizen</label>
		    		</div>
		    	</div>
		    </div>

		    <div id="US_citizen_span" @if(!$is_us_citizen) hidden @endif>
		    	<div class="row">
		      	<div class="col-xs-12 col-sm-6 form-group">
							<label for="states">
			      		State of Residence <span class="far fa-asterisk fa-xs fa-pull-right" aria-hidden="true"></span>
							</label>
				    	<select name="state" form="CitizenForm" class="form-control" id='states'>
		 						<option hidden></option>
					    	@foreach($states as $state)
		  						<option value="{{$state->StateCode}}"
										@if(GenericCitizenship::matches_expected($us_resident, "fkey_StateCode", $state->StateCode) ||
											  (empty($us_resident) && GenericCitizenship::matches_expected($ods_resident, "fkey_StateCode", $state->StateCode)) ||
												(empty($us_resident) && !empty($ods_resident->fkey_CityCode) && $state->StateCode == "VA"))
											selected
										@endif>
										{{ $state->StateName }}
									</option>
		  					@endforeach
				    	</select>
		        </div>
					</div>
			    <div id="VA_span" @if(!GenericCitizenship::matches_expected($us_resident, "fkey_StateCode", "VA") && !(empty($us_resident) && (GenericCitizenship::matches_expected($ods_resident, "fkey_StateCode", "VA") || !empty($ods_resident->fkey_CityCode)))) hidden @endif>
			      <div class="row">
			      	<div class="col-xs-12 col-md-6 form-group">
								<label for="counties">
			        		City/County of Residence <span class="far fa-asterisk fa-xs fa-pull-right" aria-hidden="true"></span>
								</label>
						    <select name="county" form="CitizenForm" class="form-control" id='counties'>
									<option hidden></option>
						    	@foreach($counties as $id => $county)
										<option value="{{ $id }}"
											@if(GenericCitizenship::matches_expected($us_resident, "fkey_CityCode", $id) ||
													(empty($us_resident) && GenericCitizenship::matches_expected($ods_resident, "fkey_CityCode", $id)))
												selected
											@endif>
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
		    		<input type="checkbox" class="hideshowbox" name="another_citizen" value=true id="other_citizen" @if(!empty($citizenship) && $citizenship->another) checked @endif />
		    		<div class="state p-primary">
		    			<label>I am a citizen of another country.</label>
		    		</div>
		    	</div>
		    </div>

		    <div class="row" id="other_citizen_span" @if(empty($citizenship) || !$citizenship->another) hidden @endif>
					<div class="row">
						<div class="col-xs-12 col-md-6">
							<div class="form-group">
						    <label for="PermanentCountry">
						      Country of permanent residence or domicile <span class="far fa-asterisk fa-xs fa-pull-right" aria-hidden="true"></span>
						    </label>
						    <select name="PermanentCountry" form="CitizenForm" class="form-control" id='PermanentCountry'>
						      <option></option>
						      @foreach($countries as $country)
						        <option @if(GenericCitizenship::matches_expected($citizenship, "permanent_residence", $country->key_CountryId)) selected @endif
						          value="{{$country->key_CountryId}}">
						          {{ $country->CountryName }}
						        </option>
						      @endforeach
						    </select>
						  </div>
						</div>
					</div>

		     	@php($foreign_count = 1)
					@if(!empty($citizenship))
			    	@foreach($citizenship->countries as $individual_country)
				     	@include("partials.citizenship_info")
					    @php($foreign_count++)
				    @endforeach
					@endif

			    @php($individual_country = NULL)

			    @if($foreign_count < 3)
			    	@for($foreign_count = $foreign_count; $foreign_count < 3; $foreign_count++)
				     	@include("partials.citizenship_info")
				    @endfor
					@endif

					<div class="row">
						<div class="col-xs-12 col-md-12 form-group">
			        <div>
								<h4 style="display: inline-block"> I am in the U.S on <span class="far fa-asterisk fa-xs fa-pull-right" aria-hidden="true"></span></h4>
							</div>
			        <div>
								<div class="pretty p-default p-round">
					    		<input type="checkbox" name="GreenCard[]" class="foreignCard" value="GreenCard" id="GreenCard"
												 @if((!empty($citizenship) && $citizenship->green_card) || (empty($citizenship) && !empty($ods_citizenship) && $ods_citizenship->green_card)) checked @endif />
					    		<div class="state p-primary p-round">
					    			<label>Permanent Residency</label>
					    		</div>
					    	</div>

					    	<div class="pretty p-default p-round">
					    		<input type="checkbox" class="foreignCard" name="GreenCard[]" value="Visa" id="Visa"
										@if(!empty($visa) || (empty($citizenship) && !empty($ods_visa))) checked @endif />
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
											<option value="" hidden @if(empty($visa)) selected @endif> -- Select a Visa Type --</option>
							    		@foreach($visa_types as $visa_type)
				  							<option value="{{ $visa_type->code }}"
													@if(GenericCitizenship::matches_expected($visa, "fkey_code", $visa_type->code) ||
															GenericCitizenship::matches_expected($ods_visa, "fkey_code", $visa_type->code)) selected @endif>
													{{ $visa_type->descr }}
												</option>
					  					@endforeach
								    </select>
									</div>
								</div>
					    </div>
						</div>
					</div>
		    </div>
				<div>
					<div class="pretty p-default">
						<input type="checkbox" class="hideshowbox" name="other_citizen" value=true id="other" @if(!empty($citizenship) && $citizenship->other) checked @endif/>
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
