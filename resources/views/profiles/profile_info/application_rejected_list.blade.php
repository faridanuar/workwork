@extends('layouts.app')

@section('content')

@include('profiles.profile_info.applications_category')

	<h4><u>Rejected List</u></h4>
	@foreach($rejectedInfos as $rejectedInfo)

		<div class="form-group">
			<a href="/my/applications/{{ $rejectedInfo->id }}">
			<div><h4>Status: {{ $rejectedInfo->status }}</h4></div>
			<div><h4>Job Request For: {{ $rejectedInfo->advert->job_title }}</h4></div>
			</a>
		</div>

	@endforeach

	{!! $rejectedInfos->render() !!}

@stop