@extends('layouts.app')

@section('content')

@include('profiles.company.requests_category')

<h1>Job Seekers</h1>
	<h4>Accepted List</h4>
	@forelse($acceptedInfos as $acceptedInfo)
		<div class="form-group">
			<a href="/advert/{{ $acceptedInfo->advert_id }}/job/requests/{{ $acceptedInfo->jobSeeker->id }}">
			<div><h4>Status: {{ $acceptedInfo->status }}</h4></div>
			<div><h4>Name: {{ $acceptedInfo->jobSeeker->user->name }}</h4></div>
			<div><h4>Age: {{ $acceptedInfo->jobSeeker->age }}</h4></div>
			<div><h4>Introduction: {{ $acceptedInfo->introduction }}</h4></div>
			</a>
		</div>
	@empty
		<p>Looks like there's no job applications yet.</p>
	@endforelse</p>
	{!! $acceptedInfos->render() !!}
	</div>
@stop