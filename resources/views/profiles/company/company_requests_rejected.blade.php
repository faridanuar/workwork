@extends('layouts.app')

@section('content')

<h1>Job Seekers</h1>

<a href="/advert/{{ $id }}/job/requests">PENDING APPLICATION LIST</a> ||
<b><a href="/advert/{{ $id }}/job/requests/rejected">REJECTED APPLICATION LIST</a></b> || 
<a href="/advert/{{ $id }}/job/requests/reviewing">ACCEPTED FOR INTERVIEW APPLICATION LIST</a>
	<hr>

	<div class="row">
		@foreach($requestInfos as $requestInfo)

		<div class="form-group">
		<a href="/advert/{{ $requestInfo->advert_id }}/job/requests/{{ $requestInfo->jobSeeker->id }}">
		<div><h4>Status: {{ $requestInfo->status }}</h4></div>
		<div><h4>Name: {{ $requestInfo->jobSeeker->user->name }}</h4></div>
		<div><h4>Age: {{ $requestInfo->jobSeeker->age }}</h4></div>
		<div><h4>Introduction: {{ $requestInfo->introduction }}</h4></div>
		</a>
		</div>

		@endforeach
	</div>

		{!! $requestInfos->render() !!}

@stop