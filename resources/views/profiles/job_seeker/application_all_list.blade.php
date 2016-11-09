@extends('layouts.app')

@section('content')

@include('profiles.job_seeker.applications_category')
<h3><u>All</u></h3>
@foreach($requestInfos as $requestInfo)

	<a href="/my/applications/{{ $requestInfo->id }}">
	<div><h4>Status: {{ $requestInfo->status }}</h4></div>
	<div><h4>Job Request For: {{ $requestInfo->advert->job_title }}</h4></div>
	</a>

@endforeach

{!! $requestInfos->render() !!}
@stop