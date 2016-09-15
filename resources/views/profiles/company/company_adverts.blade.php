@extends('layouts.app')

@section('content')


	<h1>My Adverts</h1>
	lala
	<hr>

	<div class="row">
		@foreach($myAdverts as $myAdvert)
		<a href="/adverts/{{ $myAdvert->id }}/{{ $myAdvert->job_title }}">
		<div class="form-group">
		<div><h4>Job-Title: {{ $myAdvert->job_title }}</h4></div>
		</div>
		</a>
		<div>
			@can('view_request')
	            <a href="/advert/{{ $myAdvert->id }}/job/requests" class="btn btn-primary">View Job Requests</a>
	        @endcan
		@endforeach
		</div>
	</div>

@stop