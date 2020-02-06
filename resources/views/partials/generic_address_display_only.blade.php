<div class="row">
  <div class="col-xs-12 col-md-6">
    <label>
      Country @if(isset($required) && $required)<span class="far fa-asterisk fa-xs fa-pull-right" aria-hidden="true"></span>@endif
    </label>
    <p id='country{{$postfix}}'>
      {{ $address->country_name }}
    </p>
  </div>
</div>
@if (empty($address->country_id) || $address->country_id == \App\GenericAddress::US_ID)
  <div class="row">
    <div class="col-xs-12">
      <div class="form-group">
        <label>
            Street @if(isset($required) && $required)<span class="far fa-asterisk fa-xs fa-pull-right" aria-hidden="true"></span>@endif
        </label>
        <p>
          @if(!empty($address)) {{$address->address[0] }} @else &mdash; @endif
        </p>
        <p>
          @if(!empty($address)) {{$address->address[1] }} @endif
        </p>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12 col-md-4">
      <div class="form-group">
        <label>
          City @if(isset($required) && $required)<span class="far fa-asterisk fa-xs fa-pull-right" aria-hidden="true"></span>@endif
        </label>
        <p>{{$address->city}}</p>
      </div>
    </div>

    <div class="col-xs-12 col-md-4">
      <div class="form-group address">
        <label>
          State @if(isset($required) && $required)<span class="far fa-asterisk fa-xs fa-pull-right" aria-hidden="true"></span>@endif
        </label>
        <p>
          {{ $address->state_name }}
        </p>
      </div>
    </div>

    <div class="col-xs-12 col-md-4">
      <div class="form-group">
        <label>
          Zip Code @if(isset($required) && $required)<span class="far fa-asterisk fa-xs fa-pull-right" aria-hidden="true"></span>@endif
        </label>
        <p>
          {{ $address->zip_code }}
        </p>
      </div>
    </div>
  </div>
@endif
@if (!empty($address->country_id) && $address->country_id != \App\GenericAddress::US_ID)
  <div class="row">
    <div class="col-xs-12 form-group">
      <label for="international_address{{$postfix}}">
        Address @if(isset($required) && $required)<span class="far fa-asterisk fa-xs fa-pull-right" aria-hidden="true"></span>@endif
      </label>
      <p>
        {{ $address->international_address }}
      </p>
    </div>
  </div>
@endif
