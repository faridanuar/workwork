@extends('layouts.app')

@section('content')


	<div class="flash">
		@include('messages.flash')
	</div>

	<img id="photo" src="{{ $user->avatar}}" height="200" width="200" onerror="imgError(this);"/>


	<h1>{{ $user->name }}</h1>

	<h3>{{ $user->contact }}</h3>

	<h2>{{ $jobSeeker->age }}</h2>

	<h4>Rate:</h4>


	<hr>

		<div>
		<h4>Address:</h4>
		</div>

		<div>
		{{ $jobSeeker->location }}
		</div>

		<div>
		{{ $jobSeeker->sreet }}
		</div>

		<div>
		{{ $jobSeeker->city }}
		</div>

		<div>
		{{ $jobSeeker->zip }}
		</div>

		<div>
		{{ $jobSeeker->state }}
		</div>

		<div>
		{{ $jobSeeker->country }}
		</div>

		@can('edit_info')
			@if ($authorize === true)
				<div>
				<a href="/edit-company" class="btn btn-primary">EDIT</a>
				</div>
			@else

			@endif
		@endcan


	
	@include('javaPlugins.defaultPhoto')
@stop