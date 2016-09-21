@extends('layouts.app')

@section('content')

@include('profiles.company.requests_category')
<div class="row">
	<div class="col-md-12">
		<h1>Job Seekers</h1>
			<h4>Pending List</h4>
		@forelse($requestInfos as $requestInfo)

			<a href="/advert/{{ $requestInfo->advert_id }}/job/requests/{{ $requestInfo->jobSeeker->id }}">
				<h4>Status: {{ $requestInfo->status }}</h4>
				<h4>Name: {{ $requestInfo->jobSeeker->user->name }}</h4>
				<h4>Age: {{ $requestInfo->jobSeeker->age }}</h4>
				<h4>Introduction: {{ $requestInfo->introduction }}</h4>
			</a>
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
									<form action="/advert/job/requests/{{ $requestInfo->id }}/response" method="post">
										{{ csrf_field() }}
										<h4>Send a Message</h4>
							        	<textarea type="text" name="comment" id="comment" class="form-control" rows="10" placeholder="Give feedback for rejecting" required>{{ old('comment') }}</textarea>
										<button type="submit" name="response" id="response0" class="btn btn-primary">Submit</button>
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
									<form action="/advert/job/requests/{{ $requestInfo->id }}/response" method="post">
										{{ csrf_field() }}
										<h4>Send a Message</h4>
							        	<textarea type="text" name="comment" id="comment" class="form-control" rows="10" placeholder="Setup an interview" required>{{ old('comment') }}</textarea>
										<button type="submit" name="response" id="response0" class="btn btn-primary">Submit</button>
									</form>
								</div>
					   		</div>
				     	<div class="modal-footer">
				        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				     	</div>
				    </div>
				  </div>
				</div>
		@empty
		<p>Looks like there's no job applications yet.</p>
		@endforelse
		{!! $requestInfos->render() !!}
	</div>
</div>

@stop