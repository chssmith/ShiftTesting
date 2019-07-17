<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
  	<h4>Country {{$foreign_count}}</h4>
  	Country of birth
    <select name = "BirthCountry[]" form = "CitizenForm" class ="form-control" id = 'BirthCountry{{$foreign_count}}'>
		<option></option>
    	@foreach($countries as $country)
			<option @if(!empty($individual_country) && $individual_country->BirthCountry == $country->key_CountryId) selected @endif 
			value="{{$country->key_CountryId}}"> {{ $country->CountryName }} </option>
		@endforeach
    </select>
    Country of citizenship
    <select name = "CitizenshipCountry[]" form = "CitizenForm" class ="form-control" id = 'BirthCountry{{$foreign_count}}'>
		<option></option>
    	@foreach($countries as $country)
			<option @if(!empty($individual_country) && $individual_country->CitizenshipCountry == $country->key_CountryId) selected @endif 
			value="{{$country->key_CountryId}}"> {{ $country->CountryName }} </option>
		@endforeach
    </select>

    Country of permanent residence or domicle
    <select name = "PermanentCountry[]" form = "CitizenForm" class ="form-control" id = 'BirthCountry{{$foreign_count}}'>
		<option></option>
    	@foreach($countries as $country)
			<option @if(!empty($individual_country) && $individual_country->PermanentCountry == $country->key_CountryId) selected @endif 
			value="{{$country->key_CountryId}}"> {{ $country->CountryName }} </option>
		@endforeach
    </select>
</div>