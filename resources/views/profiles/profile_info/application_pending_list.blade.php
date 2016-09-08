@extends('layouts.app')

@section('content')

@include('profiles.profile_info.applications_category')

	<h4><u>Pending List</u></h4>
	@foreach($requestInfos as $requestInfo)
	
		<a href="/my/applications/{{ $requestInfo->id }}">
		<div><h4>Status: {{ $requestInfo->status }}</h4></div>
		<div><h4>Job Request For: {{ $requestInfo->advert->job_title }}</h4></div>
		</a>

	@endforeach

	{!! $requestInfos->render() !!}

@stop