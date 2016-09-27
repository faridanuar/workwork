@extends('layouts.app')

@section('content')

@include('profiles.profile_info.applications_category')

	<h3><u>Accepted List</u></h3>
	@foreach($acceptedInfos as $acceptedInfo)

		<div class="form-group">
			<a href="/my/applications/{{ $acceptedInfo->id }}">
			<div><h4>Status: {{ $acceptedInfo->status }}</h4></div>
			<div><h4>Job Request For: {{ $acceptedInfo->advert->job_title }}</h4></div>
			</a>
		</div>

	@endforeach

	{!! $acceptedInfos->render() !!}

@stop