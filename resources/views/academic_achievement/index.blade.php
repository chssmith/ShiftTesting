@extends('forms_template')

@section('heading')
	Academic Achievement Form
@endsection

@section('javascript')
@endsection

@section("stylesheets")
	@parent
		<link type="text/css" rel="stylesheet" href="{{ asset("css/global.css") }}" />

    <style>
      .exams {
        display: grid;
        grid-gap: 20px;
        grid-template-columns: 1fr 1fr 1fr;
      }
      @media (max-width: 1200px) {
        .exams {
          grid-template-columns: 1fr 1fr;
        }
      }
      @media (max-width: 767px) {
        .exams {
          grid-template-columns: 1fr;
          grid-gap: 10px;
        }
      }
    </style>
@endsection

@section("content")
  <form action="{{ action("StudentInformationController@storeAcademicAchievement") }}" method="POST">

    {{ csrf_field() }}

    <h3>Advanced Placement Examinations</h3>

    <div>
      <p>
        Please check any AP exams you have taken, or plan to take:
      </p>
      <div class="exams" id="AP_exams">
        @foreach ($ap_exams as $ap_exam)
          <label for="ap_{{ $ap_exam->id }}"><input type="checkbox" name="ap_exams[]" value="{{ $ap_exam->id }}" id="ap_{{ $ap_exam->id }}" @if (!empty($ap_exam->map) && !$ap_exam->map->isEmpty()) checked @endif/> {{ $ap_exam->name }}</label>
        @endforeach
      </div>
    </div>

    <h3>International Baccalaureate Examinations</h3>

    <div>
      <p>
        Please check any IB exams you have taken, or plan to take:
      </p>
      <div class="exams" id="IB_exams">
        @foreach ($ib_exams as $ib_exam)
          <label for="ib_{{ $ib_exam->id }}"><input type="checkbox" name="ib_exams[]" value="{{ $ib_exam->id }}" id="ib_{{ $ib_exam->id }}" @if (!empty($ib_exam->map) && !$ib_exam->map->isEmpty()) checked @endif/> {{ $ib_exam->name }}</label>
        @endforeach
      </div>
    </div>

    <div class="row">
      <div class="col-sm-10 col-xs-12" style="text-align: right;">
        <button type="submit" class="btn btn-complete btn-lg">Submit</button>
      </div>
    </div>
  </form>

@endsection
