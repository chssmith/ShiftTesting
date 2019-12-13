@extends('forms_template')

@section('heading')
	Admin
@endsection

@section('javascript')
@endsection

@section('stylesheets')
@endsection

@section("content")
	<div class="list-group">
    	<a href="{{action('AdminController@changedStudents')}}" class="list-group-item"> Changed Student Forms</a>
    	<a href="{{action('AdminController@changedParentInfo')}}" class="list-group-item"> Changed Parent Info </a> 
    </div>
@endsection	