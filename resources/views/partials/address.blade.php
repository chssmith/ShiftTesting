<div class="row">
  <div class="col-xs-12 col-md-6 form-group">
    <label for="country{{$postfix}}">
      Country @if(isset($required) && $required)<span class="fas fa-star fa-xs fa-pull-right" aria-hidden="true"></span>@endif
    </label>
    <select name="country{{$postfix}}" class="form-control" id='country{{$postfix}}'>
      <option hidden></option>
      @foreach($countries->sortBy("CountryName") as $country)
        <option @if ((empty($address->country_id) && $country->key_CountryId == \App\GenericAddress::US_ID) || ($address->country_id == $country->key_CountryId)) selected @endif value="{{$country->key_CountryId}}">
          {{ $country->CountryName }}
        </option>
      @endforeach
    </select>
  </div>
</div>
<span id="US{{$postfix}}" @if (!empty($address->country_id) && $address->country_id != \App\GenericAddress::US_ID) hidden @endif>
  <div class="row">
    <div class="col-xs-12">
      <div class="form-group">
        <label for="address_1{{$postfix}}">
            Street @if(isset($required) && $required)<span class="fas fa-star fa-xs fa-pull-right" aria-hidden="true"></span>@endif
        </label>
        <input type="text" class="form-control" name="address{{$postfix}}[]" id="address_1{{$postfix}}" @if(!empty($address)) value="{{$address->address[0]}}" @endif>
      </div>
    </div>

    <div class="col-xs-12">
      <div class="form-group address">
        <input type="text" class="form-control" name="address{{$postfix}}[]" id="address_2{{$postfix}}" @if(!empty($address)) value="{{$address->address[1]}}" @endif>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12 col-md-4">
      <div class="form-group">
        <label for="city{{$postfix}}">
          City @if(isset($required) && $required)<span class="fas fa-star fa-xs fa-pull-right" aria-hidden="true"></span>@endif
        </label>
        <input type="text" class="form-control" name="city{{$postfix}}" id="city{{$postfix}}" @if(!empty($address)) value="{{$address->city}}" @endif>
      </div>
    </div>

    <div class="col-xs-12 col-md-4">
      <div class="form-group address">
        <label for="state{{$postfix}}">
          State @if(isset($required) && $required)<span class="fas fa-star fa-xs fa-pull-right" aria-hidden="true"></span>@endif
        </label>
        <select id="state{{$postfix}}" name="state{{$postfix}}" class="form-control">
          <option hidden></option>
          @foreach($states->sortBy("StateName") as $state)
            <option value='{{$state->StateCode}}' @if((empty($address->state) && $state->StateCode == 'VA') || (!empty($address) && ($address->state == $state->StateCode))) selected @endif>
              {{$state->StateName}}
            </option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="col-xs-12 col-md-4">
      <div class="form-group">
        <label for="zip{{$postfix}}">
          Zip Code @if(isset($required) && $required)<span class="fas fa-star fa-xs fa-pull-right" aria-hidden="true"></span>@endif
        </label>
        <input type="text" class="form-control" name="zip{{$postfix}}" id="zip{{$postfix}}" @if(!empty($address)) value="{{$address->zip_code}}" @endif>
      </div>
    </div>
  </div>
</span>
<span id="international{{$postfix}}" @if (empty($address->country_id) || $address->country_id == \App\GenericAddress::US_ID) hidden @endif>
  <div class="row">
    <div class="col-xs-12 form-group">
      <label for="international_address{{$postfix}}">
        Address @if(isset($required) && $required)<span class="fas fa-star fa-xs fa-pull-right" aria-hidden="true"></span>@endif
      </label>
      <textarea name="international_address{{$postfix}}" id="international_address{{$postfix}}" rows="5" columns="200" class="form-control">{{ $address->international_address }}</textarea>
    </div>
  </div>
</span>
<script>
  $(document).on("change", "#country{{$postfix}}", function (evt) {
    console.log($(this).val());
    if ($(this).val() != 273) {
      $("#US{{$postfix}}").hide();
      $("#international{{$postfix}}").show();
    } else {
      $("#US{{$postfix}}").show();
      $("#international{{$postfix}}").hide();
    }
  });
</script>
