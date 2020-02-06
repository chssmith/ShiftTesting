@extends('forms_template')

@section('heading')
  Summer Orientation Registration Admin
@endsection

@section("header")
@endsection

@section('javascript')
  @parent
  <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function () {
      $(".datatable").DataTable();
    });
  </script>
@endsection

@section("stylesheets")
  @parent
	<link type="text/css" rel="stylesheet" href="{{ asset("css/global.css") }}" />
  <link type="text/css" rel="stylesheet" href="{{ asset("css/dark_table.css") }}" />
  <link type="text/css" rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" />
  <style>
    .list-group-item {
      font-size: 16pt;
    }
  </style>
@endsection

@section("content")
  <h2>Registration Report</h2>

  <div class="row" style="padding-bottom: 20px">
    <div class="col-xs-6" style="text-align: left">
      <a href="{{ url()->previous() }}" class="btn btn-primary"><span class="far fa-arrow-left" aria-hidden="true"></span> Back</a>
    </div>
    <div class="col-xs-6" style="text-align: right">
      <a href="{{ action("SIMSRegistrationController@adminRegistrationReportExcel") }}" class="btn btn-primary"><span class="far fa-download" aria-hidden="true"></span> Download Excel</a>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12">
      @include("sims.admin.stage1.partials.report_table")
    </div>
  </div>

@endsection
