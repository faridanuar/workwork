@extends('layouts.app')

@section('content')

<h1>Job Seekers</h1>

	<hr>

	<div class="row">
		@foreach($requestInfos as $requestInfo)
		<div class="form-group">
		<a href="/profile/{{ $requestInfo->jobSeeker->id }}/{{ $requestInfo->jobSeeker->user_id }}">
		<div><h4>Status: {{ $requestInfo->status }}</h4></div>
		<div><h4>Name: {{ $requestInfo->jobSeeker->user->name }}</h4></div>
		<div><h4>Age: {{ $requestInfo->jobSeeker->age }}</h4></div>
		<div><h4>Introduction: {{ $requestInfo->introduction }}</h4></div>
		</a>
		@if($requestInfo->responded === 0 )
			<form action="/advert/job/requests/{{ $requestInfo->id }}/response" method="post">
			{{ csrf_field() }}
				<button type="button" name="rejectBtn" class="btn btn-primary" data-toggle="modal" data-target="#reasonModal" value="REJECTED">Reject</button>
				<button type="submit" name="response" id="response1" class="btn btn-primary" value="IN REVIEW">In Review</button>
				<!-- Modal -->
				<div id="reasonModal" class="modal fade" role="dialog">
				  <div class="modal-dialog">

				    <!-- Modal content-->
				    <div class="modal-content">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal">&times;</button>
				        <h4 class="modal-title">Tell us your reason</h4>
				      </div>
				      <div class="modal-body">
				        <textarea type="text" name="comment" id="comment" class="form-control" rows="10" placeholder="Give your reason">{{ old('comment') }}</textarea>
						<button type="submit" name="response" id="response0" class="btn btn-primary" value="REJECTED" >Submit</button>
				      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				      </div>
				    </div>

				  </div>
				</div>
			</form>
		@else

		@endif
		</div>

		@endforeach
	</div>

@stop