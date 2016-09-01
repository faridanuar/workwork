@extends('layouts.app')

@section('content')

@include('profiles.company.requests_category')

<h1>Job Seekers</h1>
	<h4>Rejected List</h4>
	@foreach($rejectedInfos as $rejectedInfo)

		<div class="form-group">
			<a href="/advert/{{ $rejectedInfo->advert_id }}/job/requests/{{ $rejectedInfo->jobSeeker->id }}">
			<div><h4>Status: {{ $rejectedInfo->status }}</h4></div>
			<div><h4>Name: {{ $rejectedInfo->jobSeeker->user->name }}</h4></div>
			<div><h4>Age: {{ $rejectedInfo->jobSeeker->age }}</h4></div>
			<div><h4>Introduction: {{ $rejectedInfo->introduction }}</h4></div>
			</a>
		</div>

	@endforeach
	{!! $rejectedInfos->render() !!}
@stop