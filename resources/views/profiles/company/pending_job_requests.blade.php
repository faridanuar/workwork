@extends('layouts.app')

@section('content')

@include('profiles.company.requests_category')
<div class="row">
	<div class="col-md-12">
		<h1>Job Seekers</h1>
			<h3><u>Pending List</u></h3>
		{{--
		@if (count($errors) > 0)
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
		@endif
		--}}

		@forelse($requestInfos as $requestInfo)
		<div class="form-group">
			<a href="/advert/{{ $requestInfo->advert_id }}/job/requests/{{ $requestInfo->id }}">
				ID: {{ $requestInfo->id }}
				<h4>Status: {{ $requestInfo->status }}</h4>
				<h4>Name: {{ $requestInfo->jobSeeker->user->name }}</h4>
				<h4>Age: {{ $requestInfo->jobSeeker->age }}</h4>
				<h4>Introduction: {{ $requestInfo->introduction }}</h4>
			</a>

			{{--
			@if($requestInfo->responded != 1)
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#reason-modal-1" >Reject</button>
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#reason-modal-2" >Accept For Interview</button>
			@endif
			--}}
			
		@empty
			<p>Looks like there's no job applications yet.</p>
		@endforelse

			@if($currentPlan != "Trial")
				{!! $requestInfos->render() !!}
			@else
				If you want unlimited view for job requests, you must purchase the premium advert plan
			@endif
		</div>
	</div>
</div>

{{-- <!-- Modal 1-->
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
					<form action="/advert/job/requests/{{ $requestInfo->id }}/response" method="post">
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
</div> --}}

@stop