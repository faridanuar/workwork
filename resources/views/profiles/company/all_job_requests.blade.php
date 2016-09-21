@extends('layouts.app')

@section('content')

@include('profiles.company.requests_category')

<h1>Job Seekers</h1>
	<h4>All List</h4>
	@foreach($allInfos as $allInfo)

		<div class="form-group">
			<a href="/advert/{{ $allInfo->advert_id }}/job/requests/{{ $allInfo->jobSeeker->id }}">
			<div><h4>Status: {{ $allInfo->status }}</h4></div>
			<div><h4>Name: {{ $allInfo->jobSeeker->user->name }}</h4></div>
			<div><h4>Age: {{ $allInfo->jobSeeker->age }}</h4></div>
			<div><h4>Introduction: {{ $allInfo->introduction }}</h4></div>
			</a>
		</div>

	@endforeach
	{!! $allInfos->render() !!}
@stop