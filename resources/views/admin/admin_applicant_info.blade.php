@extends('layouts.app')
@section('content')
<div class="flash">
	@include('messages.flash')
</div>

<div class="ftu-panel panel-ww-600 panel panel-default center-block">
    <div class="panel-heading panel-heading-ww">Requested For A Job As: {{ $application->advert->job_title }}</div>
		<div class="panel-body">
			<div class="form-group">
				<a href="/a/dashboard" class="btn btn-default btn-sm">Back</a>
			</div>

			<hr>

			<div>
				@if($average)
					{{ $average }} out of 5 STAR
				@else
					No ratings yet
				@endif
			</div>

			<hr>

			<img src="{{ $photo }}" height="100" width="100"/>

			<hr>

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
				
				@if ($application->status == 'pending'):
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#reason-modal-1" >Reject</button>
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#reason-modal-2" >Accept For Interview</button>
				@endif

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
									<form action="/a/job/requests/{{ $application->id }}/response" method="post" name="rejectForm">
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
									<form action="/a/job/requests/{{ $application->id }}/response" method="post" name ="acceptForm">
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

			<p></p>

			<div>
				{{ $jobSeeker->user->name }}
			</div>

			<div>
				{{ $jobSeeker->user->contact }}
			</div>

			<div>
				{{ $jobSeeker->age }}
			</div>

			<hr>

			<div>
				Introduction:
				<p>{!! nl2br(e($application->introduction)) !!}</p>
			</div>

			<hr>

			<div>
				Address:
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

			<a href="/profile/{{ $jobSeeker->id }}/review">Reviews ({{ $ratings }})</a>

			@can('rate_jobSeeker')
				@if($application->responded === 1)
					@if($rated === false)
						@if($application->status != "REJECTED")
							<h4>Rate:</h4>

							<form action="/profile/{{ $jobSeeker->id }}/rate" method="post" id="rateForm">
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

				            <p></p>

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
				@endif
			@endcan
		</div>
	</div>
</div>
@stop

@section('js_plugins')
	<script type="text/javascript">
	$(document).ready(function(){
	    $.ajax({
	      type: "POST",
	      url: "/viewed/applicant",
	      context: document.body,
	      data: {
	      		'status': '{{ $application->status }}',
	            'applicationID': '{{ $application->id }}',
	            '_token': '{!! csrf_token() !!}'
	            }
	    });
	});
	</script>
@stop