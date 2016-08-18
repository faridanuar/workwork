@extends('layouts.app')

@section('content')

<div class="flash">
		@include('messages.flash')
	</div>

	<h1>Applied Job Seeker Profile:</h1>

	<img src="{{ $photo }}" height="200" width="200"/>

	<h2>{{ $average }} out of 5 (STAR)</h2>

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

		<a href="/profile/{{ $profileInfo->id }}/review">Reviews ({{ $ratings }})</a>

		@can('rate_jobSeeker')
			@if($responded === 1)
				@if($rated === false)
					<h4>Rate:</h4>

					<form action="/profile/{{ $profileInfo->id }}/rate" method="post" id="rateForm">
					{{ csrf_field() }}

					<div class="btn-group" data-toggle="buttons">
				        <label class="btn btn-primary">
					        <input type="radio" name="star" id="star0" autocomplete="off" value="1"> 1 Star
				        </label>
				        <label class="btn btn-primary">
					        <input type="radio" name="star" id="star1" autocomplete="off" value="2"> 2 Star
				        </label>
				        <label class="btn btn-primary">
					        <input type="radio" name="star" id="star2" autocomplete="off" value="3"> 3 Star
				        </label>
				        <label class="btn btn-primary">
					        <input type="radio" name="star" id="star3" autocomplete="off" value="4"> 4 Star
				        </label>
				        <label class="btn btn-primary">
					        <input type="radio" name="star" id="star4" autocomplete="off" value="5"> 5 Star
				        </label>
		            </div>
		            <p>
		            <div class="form-group-comment">
						<label for="comment">Comment:</label>
						<textarea type="text" name="comment" id="comment" class="form-control" rows="5" required>{{ old('comment') }}</textarea>
					</div>
					<button type="submit" class="btn btn-primary">Submit</button>

					</form>

					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<ul>
								@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
				@else

				@endif
			@endif
		@endcan

@stop