<div class="col-xs-12 col-sm-6">
  <h4>Country {{$foreign_count}}</h4>

  <div class="form-group">
    <label for="CitizenshipCountry{{ $foreign_count }}">
      Country of citizenship
    </label>
    <select name = "CitizenshipCountry[]" form="CitizenForm" class="form-control" id='CitizenshipCountry{{$foreign_count}}'>
      <option></option>
    	@foreach($countries as $country)
        <option @if(!empty($individual_country) && $individual_country->CitizenshipCountry == $country->key_CountryId) selected @endif
  		       value="{{$country->key_CountryId}}">
             {{ $country->CountryName }}
        </option>
  	  @endforeach
    </select>
  </div>
  <div class="form-group">
    <label for="PermanentCountry{{ $foreign_count }}">
      Country of permanent residence or domicle
    </label>
    <select name="PermanentCountry[]" form="CitizenForm" class="form-control" id='PermanentCountry{{ $foreign_count }}'>
      <option></option>
      @foreach($countries as $country)
        <option @if(!empty($individual_country) && $individual_country->PermanentCountry == $country->key_CountryId) selected @endif
          value="{{$country->key_CountryId}}">
          {{ $country->CountryName }}
        </option>
      @endforeach
    </select>
  </div>
</div>
