@extends('layouts.app')

@section('content')


	<div class="flash">
		@include('messages.flash')
	</div>

	<h1>Job Seeker Profile:</h1>

	<img id="photo" src=".{{ $photo }}" height="200" width="200"/>

	<h2>{{ $average }} out of 5 (STAR)</h2>

	<h4>Ratings: {{ $ratings }}</h4>

	<h1>{{ $profileInfo->user->name }}</h1>

	<h3>{{ $profileInfo->user->contact }}</h3>

	<h2>{{ $profileInfo->age }}</h2>


	<hr>

		<div>
		<h4>Address:</h4>
		</div>

		<div>
		{{ $profileInfo->location }}
		</div>

		<div>
		{{ $profileInfo->sreet }}
		</div>

		<div>
		{{ $profileInfo->city }}
		</div>

		<div>
		{{ $profileInfo->zip }}
		</div>

		<div>
		{{ $profileInfo->state }}
		</div>

		<div>
		{{ $profileInfo->country }}
		</div>

		<a href="/profile/{{ $profileInfo->id }}/review">Reviews</a>

		@can('edit_info')
			@if ($authorize === true)
				<div>
				<a href="/profile/edit" class="btn btn-primary">EDIT</a>
				</div>
			@else

			@endif
		@endcan
@stop