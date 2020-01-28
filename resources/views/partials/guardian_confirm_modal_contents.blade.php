<h4>Parent / Guardian Information</h4>
<div class="row">
  <div class="col-xs-12 col-sm-6">
    <div class="form-group">
      <label for="first_name">
        First Name <span class="far fa-asterisk fa-xs fa-pull-right" aria-hidden="true"></span>
      </label>
      <p>
        {{$guardian->first_name}}
      </p>
    </div>
  </div>

  <div class="col-xs-12 col-sm-6">
    <div class="form-group">
      <label for="nick_name">
        Nick Name
      </label>
      <p>
        {{$guardian->nick_name}}
      </p>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-xs-12 col-sm-6">
    <div class="form-group">
      <label for="middle_name">
        Middle Name
      </label>
      <p>
        {{$guardian->middle_name}}
      </p>
    </div>
  </div>

  <div class="col-xs-12 col-sm-6">
    <div class="form-group">
      <label for="last_name">
        Last Name <span class="far fa-asterisk fa-xs fa-pull-right" aria-hidden="true"></span>
      </label>
      <p>
        {{$guardian->last_name}}
      </p>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-xs-12 col-sm-6">
    <div class="form-group">
      <label for="relationship">
        Relationship  <span class="far fa-asterisk fa-xs fa-pull-right" aria-hidden="true"></span>
      </label>
      <p>
        {{$guardian->relationship}}
      </p>
    </div>
  </div>

  <div class="col-xs-12 col-sm-6">
    <div class="form-group">
      <label for="marital_status">
        Marital Status <span class="far fa-asterisk fa-xs fa-pull-right" aria-hidden="true"></span>
      </label>
      <p>
        @if(!empty($guardian->marital_status))
          {{ $guardian->marital_status->description }}
        @endif
      </p>
    </div>
  </div>
</div>


<h4> Contact Information </h4>
<div class="row">
  <div class="col-xs-12">
    <div class="form-group">
      <label for="email">
        Email
      </label>
      <p>
        {{ $guardian->email }}
      </p>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-xs-12 col-sm-6">
    <div class="form-group">
      <label for="home_phone">
        Home Phone
      </label>
      <p>
        {{$guardian->home_phone}}
      </p>
    </div>
  </div>

  <div class="col-xs-12 col-sm-6">
    <div class="form-group">
      <label for="cell_phone">
        Cell Phone
      </label>
      <p>
        {{$guardian->cell_phone}}
      </p>
    </div>
  </div>
</div>

<h4> Home Address</h4>
@include("partials.generic_address_display_only", ['address' => $guardian_address, "postfix" => "", 'required' => true])

<div class="form-group">
  <h4> Joint mailing address</h4>
  <div class="col-xs-12">
    <div class="form-group">
      <p>
        {{$guardian->joint_mail1}}
      </p>
      <p>
        {{$guardian->joint_mail2}}
      </p>
    </div>
  </div>
</div>



<h4> Additional Information </h4>
<div class="row">
  <div class="col-xs-12 col-md-6">
    <div class="form-group">
      <label>
        Highest Education <span class="far fa-asterisk fa-xs fa-pull-right" aria-hidden="true"></span>
      </label>
      <p style="margin-left: 20px">
        @if (isset($guardian->education))
          {{ $guardian->education->education }}
        @else
          &#9;&ndash;
        @endif
      </p>
    </div>
  </div>

  <div class="col-xs-12">
    <div>
      <label>
        I reside with this parent/guardian
      </label>
      @if ($guardian->reside_with)
        <span class="far fa-check-square" aria-label="check"></span>
      @else
        <span class="far fa-square" aria-label="no check"></span>
      @endif
    </div>
    <div>
      <label>
        This parent/guardian claims me as a tax dependent
      </label>
      @if ($guardian->claimed_dependent)
        <span class="far fa-check-square" aria-label="check"></span>
      @else
        <span class="far fa-square" aria-label="no check"></span>
      @endif
    </div>
  </div>
</div>

@if(!empty($guardian->employment))
  @php($employment = $guardian->employment)
  <h4> Employment Information </h4>
  <div class="row">
    <div class="col-xs-12 col-sm-6">
      <div class="form-group">
        <label>
          Employer Name
        </label>
        <p>
          @if(!empty($employment->employer_name))
            {{$employment->employer_name}}
          @else
            &mdash;
          @endif
        </p>
      </div>
    </div>
    <div class="col-xs-12 col-sm-6">
      <div class="form-group">
        <label>
          Position
        </label>
        <p>
          @if(!empty($employment->position))
            {{$employment->position}}
          @else
            &mdash;
          @endif
        </p>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12 col-sm-6">
      <div class="form-group">
        <label>
          Business Phone Number
        </label>
        <p>
          @if(!empty($employment->employer_name))
            {{$employment->employer_name}}
          @else
            &mdash;
          @endif
        </p>
      </div>
    </div>

    <div class="col-xs-12 col-sm-6">
      <div class="form-group">
        <label for="business_email">
          Business Email
        </label>
        <p>
          @if(!empty($employment->business_email))
            {{$employment->business_email}}
          @else
            &mdash;
          @endif
        </p>
      </div>
    </div>
  </div>
  <div style="margin-left: 20px;">
    <h5> Business Address </h5>

    @include("partials.generic_address_display_only", ['address' => $employment_address, "postfix" => "_business"])
  </div>
@endif
