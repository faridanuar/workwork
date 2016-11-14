@extends('layouts.app')

@section('content')

<div class="flash">
	@include('messages.flash')
</div>

<a href="/advert/{{$id}}/job/requests/pending">Back</a>

	<h1>Applied Job Seeker Profile:</h1>

	<img src="{{ $photo }}" height="200" width="200"/>

	<h2>{{ $average }} out of 5 STAR</h2>

	<h1>{{ $profileInfo->user->name }}</h1>

	<h3>{{ $profileInfo->user->contact }}</h3>

	<h2>{{ $profileInfo->age }}</h2>

		@if($application->responded != 1)
			@if (count($errors) > 0)
				<div class="alert alert-danger">
					<ul>
						@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif
			
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#reason-modal-1" >Reject</button>
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#reason-modal-2" >Accept For Interview</button>

			<!-- Modal 1-->
				<div id="reason-modal-1" class="modal fade" role="dialog">
				  <div class="modal-dialog">

				    <!-- reason Modal content-->
				    <div class="modal-content">
				      	<div class="modal-header">
				        	<button type="button" class="close" data-dismiss="modal">&times;</button>
				        	<h4 class="modal-title">Your Response</h4>
				      	</div>
				     	 	<div class="modal-body">
								<div>
									<form action="/advert/job/requests/{{ $application->id }}/response" method="post">
										{{ csrf_field() }}
										<h4>Send a Message</h4>
							        	<textarea type="text" name="feedback" id="feedback" class="form-control" rows="10" placeholder="Give feedback for rejecting" required>{{ old('comment') }}</textarea>
										<button type="submit" class="btn btn-primary" name="status" id="status" value="REJECTED">Submit</button>
									</form>
								</div>
					   		</div>
				     	<div class="modal-footer">
				        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				     	</div>
				    </div>
				  </div>
				</div>

				<!-- Modal 2-->
				<div id="reason-modal-2" class="modal fade" role="dialog">
				  <div class="modal-dialog">

				    <!-- reason Modal content-->
				    <div class="modal-content">
				      	<div class="modal-header">
				        	<button type="button" class="close" data-dismiss="modal">&times;</button>
				        	<h4 class="modal-title">Your Response</h4>
				      	</div>
				     	 	<div class="modal-body">
								<div>
									<form action="/advert/job/requests/{{ $application->id }}/response" method="post">
										{{ csrf_field() }}
										<h4>Send a Message</h4>
							        	<textarea type="text" name="arrangement" id="arrangement" class="form-control" rows="10" placeholder="Setup an interview" required>{{ old('comment') }}</textarea>
										<button type="submit" class="btn btn-primary" name="status" id="status" value="ACCEPTED FOR INTERVIEW">Submit</button>
									</form>
								</div>
					   		</div>
				     	<div class="modal-footer">
				        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				     	</div>
				    </div>
				  </div>
				</div>
			@endif
	<hr>

	<div>
		<h4>Introduction: {{ $request->introduction }}</h4>
	</div>

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
			@if($request->responded === 1)
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
				@endif
			@endif
		@endcan

@stop