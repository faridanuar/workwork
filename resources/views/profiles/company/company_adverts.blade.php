@extends('layouts.app')

@section('content')

	<h2>Adverts</h2>

	<hr>

	<div class="row">
		<div class="col-sm-8">
			@forelse($myAdverts as $myAdvert)
				<div class="panel panel-default">
					<div class="panel-body">
						<a href="/adverts/{{ $myAdvert->id }}/{{ strtolower($myAdvert->job_title) }}">
						<h4>{{ $myAdvert->job_title }}</h4>
						</a>
						@can('view_request')
							<a href="/adverts/{{ $myAdvert->id }}/{{ strtolower($myAdvert->job_title) }}/edit" class="btn btn-default">
							Edit</a>
							<a href="/adverts/{{ $myAdvert->id }}/{{ strtolower($myAdvert->job_title) }}" class="btn btn-default">
							Preview</a>
				            <a href="/advert/{{ $myAdvert->id }}/job/requests" class="btn btn-default">View Job Requests</a>
				        @endcan
					</div>
				</div>
			@empty
				<p>You have no part-time advertisements yet, lets create one...
				@can('edit_advert')
	                <a class="btn btn-primary btn-lg btn-ww-lg" href="{{ url('/adverts/create') }}">Create Part-time Ad</a>
	            @endcan
			@endforelse</p>
		</div>
		@if (count($myAdverts) > 0)
			<div class="col-sm-4">
				<div class="panel panel-default">
					<div class="panel-body">
						<p>
							This is where you view all your part-time adverts.
						</p>
						@can('edit_advert')
		                    <a class="btn btn-primary btn-lg btn-block btn-ww-lg" href="{{ url('/adverts/create') }}">Create Part-time Ad</a>
		                @endcan
	                </div>
				</div>
			</div>
		@endif
	</div>

<!-- 	<div class="row">
		@foreach($myAdverts as $myAdvert)
		<a href="/adverts/{{ $myAdvert->id }}/{{ $myAdvert->job_title }}">
		<div class="form-group">
		<div><h4>Job-Title: {{ $myAdvert->job_title }}</h4></div>
		</div>
		</a>
		<div>
			@can('view_request')
	            <a href="/advert/{{ $myAdvert->id }}/job/requests" class="btn btn-primary">View Job Requests</a>
	        @endcan
		@endforeach
		</div>
	</div> -->

@stop