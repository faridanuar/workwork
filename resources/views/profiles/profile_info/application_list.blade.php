@extends('layouts.app')

@section('content')

<a href="/dashboard">Back</a>

@foreach($applications as $application)
	<div class="application-list">
		<a href="/my/applications/{{ $application->id }}">
		<h4>Job Request From: {{$application->advert->job_title}}</h4>
		Status: {{$application->status}}
		</a>
	</div>
@endforeach

{!! $applications->render() !!}

@stop