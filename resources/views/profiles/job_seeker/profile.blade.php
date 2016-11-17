@extends('layouts.app')

@section('content')


	<div class="flash">
		@include('messages.flash')
	</div>

<div class="panel panel-default">
	<div class="panel-body">
		<div>
			My Job Seeker Profile:
		</div>

		<hr>

		<img id="photo" src="{{ $photo }}" height="100" width="100"/>

		<hr>

		<div>
			@if($average)
				{{ $average }} out of 5 STAR
			@else
				No ratings yet
			@endif
		</div>

		<div>
			{{ $profileInfo->user->name }}
		</div>

		<div>
			{{ $profileInfo->user->contact }}
		</div>

		<div>
			{{ $profileInfo->age }}
		</div>

		<hr>

		<div>
			<div>
				Address:
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
		</div>

		<div>
			<a href="/profile/{{ $profileInfo->id }}/review">Reviews ({{ $ratings }})</a>
		</div>

		@can('edit_info')
			@if ($authorize === true)
				<hr>

				<div>
					<a href="/profile/info/edit" class="btn btn-primary">EDIT</a>
				</div>
			@endif
		@endcan
	</div>
</div>
@stop