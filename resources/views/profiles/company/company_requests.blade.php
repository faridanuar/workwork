@extends('layouts.app')

@section('content')

<h1>Job Seekers</h1>

	<hr>

	<div class="row">
		@foreach($requests as $request)
		<a href="/profile/{{ $request->jobSeeker->id }}/{{ $request->jobSeeker->user_id }}">
		<div class="form-group">
		<div><h4>Name: {{ $request->jobSeeker->user->name }}</h4></div>
		<div><h4>Age: {{ $request->jobSeeker->age }}</h4></div>
		<div><h4>job seeker id: {{ $request->job_seeker_id }}</h4></div>
		<div>Status: {{ $request->status }}</div>
		</div>
		</a>
		@endforeach
	</div>

@stop