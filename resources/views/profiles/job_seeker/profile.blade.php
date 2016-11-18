@extends('layouts.app')
@section('content')
<div class="flash">
	@include('messages.flash')
</div>

<div class="ftu-panel panel-ww-600 panel panel-default center-block">
    <div class="panel-heading panel-heading-ww">Job Seeker Profile:</div>
		<div class="panel-body">
			<div>
				@if($average)
					{{ $average }} out of 5 STAR
				@else
					No ratings yet
				@endif
			</div>

			<hr>

			<img id="photo" src="{{ $photo }}" height="100" width="100"/>

			<hr>

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
</div>
@stop