@extends('forms_template')

@section('javascript')
@endsection

@section('heading')
  Financial Acceptance Statement
@endsection

@section("stylesheets")
	@parent
  <style>
    .list-group {
      display: grid;
      grid-template-rows: repeat(6, 1fr);
    }
    .pretty label {
      white-space: normal;
    }
    @media(max-width: 1000px) {
      .pretty {
        width: 100%;
      }
      .pretty .state label {
        display: grid;
        grid-template-areas: "checkbox label";
        grid-template-columns: 40px 1fr;
      }
      .pretty .state label:before {
        grid-area: checkbox;
        position: initial;
      }
      .pretty .state label > span {
        grid-area: label;
        text-indent: 0px;
      }
    }


  </style>
@endsection

@section("content")
  <div class="panel">
    <div class="panel-body">
      <div class="list-group">
        <p class="list-group-item">
          Enrollment at Roanoke College involves my assumption of a definite
          financial responsibility in which I am responsible for all costs and
          charges incurred and agree to remit payment by due dates as published by
          the College, <a href="">Billing Timeline</a>
        </p>
        <p class="list-group-item">
          The College presents billing statements electronically in MyRoanoke
          student portal and is my responsibility to view billing and share
          information with the payer of my account.
        </p>
        <p class="list-group-item">
          If I do not make the required payment and my account becomes unpaid debt,
          I realize I may be prevented from registering for future terms,
          participating in housing selection, or obtaining official documents such
          as transcripts and diplomas.
        </p>
        <p class="list-group-item">
          I agree that any unpaid balance to Roanoke College will be treated as an
          extension of credit from the College in the form of an educational loan;
          thereby it is not dischargeable in bankruptcy.
        </p>
        <p class="list-group-item">
          If any educational debt is not paid in full, it could be turned over to an
          outside collection agency and/or reported to the credit bureau.  If such
          action is required, I will be liable for all collection fees, reasonable
          attourneys fees, and any associated court costs.
        </p>
        <p class="list-group-item">
          I authorize Roanoke College and its representatives (including collection
          agencies) to contact me through my home phone, mobile phone, and email
          addresses on record with the College.
        </p>
      </div>


      <form action="{{ action("StudentInformationController@completeFinancialAcceptance") }}" method="POST">
        {{ csrf_field() }}
        <div>
          <div class="pretty p-default">
            <input type="checkbox" name="acknowledge" value=true id="acknowledge" @if($student->financial_acceptance) checked @endif />
            <div class="state p-primary">
              <label>
                <span>By checking this box, I acknowledge, understand, and accept these terms.</span>
              </label>
            </div>
          </div>
        </div>
        <div style="text-align: right">
          <button type="submit" class="btn btn-secondary">Accept</button>
        </div>
      </form>
    </div>
  </div>
@endsection
