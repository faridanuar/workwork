@extends('layouts.app')

@section('content')

@include('profiles.company.requests_category')

<h1>Job Seekers</h1>

	<h4>Pending List</h4>
	@foreach($requestInfos as $requestInfo)
	
		<a href="/advert/{{ $requestInfo->advert_id }}/job/requests/{{ $requestInfo->jobSeeker->id }}">
		<div><h4>Status: {{ $requestInfo->status }}</h4></div>
		<div><h4>Name: {{ $requestInfo->jobSeeker->user->name }}</h4></div>
		<div><h4>Age: {{ $requestInfo->jobSeeker->age }}</h4></div>
		<div><h4>Introduction: {{ $requestInfo->introduction }}</h4></div>
		</a>
		@if($requestInfo->responded === 0 )
			<form action="/advert/job/requests/{{ $requestInfo->id }}/response" method="post">
			{{ csrf_field() }}

				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#reasonModal" >Respond</button>

				<!-- Modal 1-->
				<div id="reasonModal" class="modal fade" role="dialog">
				  <div class="modal-dialog">

				    <!-- reason Modal content-->
				    <div class="modal-content">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal">&times;</button>
				        <h4 class="modal-title">Your Response</h4>
				      </div>
				      <div class="modal-body">
					        <div class="btn-group" data-toggle="buttons">
							  <label class="btn btn-primary">
							    <input type="radio" name="status" id="status0" value="REJECTED" autocomplete="off" required> Reject
							  </label>
							  <label class="btn btn-primary">
							    <input type="radio" name="status" id="status1" value="ACCEPTED FOR INTERVIEW" autocomplete="off"> Accept for Interview
							  </label>
							</div>
							<div>
							<h4>Send a Message</h4>
						        <textarea type="text" name="comment" id="comment" class="form-control" rows="10" placeholder="Setup an interview OR give feedback for rejecting" required>{{ old('comment') }}</textarea>
								<button type="submit" name="response" id="response0" class="btn btn-primary">Submit</button>
							</div>
					   </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				      </div>
				    </div>
				  </div>
				</div>
			</form>
		@endif

	@endforeach
	{!! $requestInfos->render() !!}
@stop