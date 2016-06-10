@extends('layouts.app')

@section('content')


<h1>Part-time Jobs</h1>

<div class="row">
	<div class="col-md-3">
		<div class="panel panel-default">
			<!-- todo Filter -->
			<div class="panel-body">
			Filter jobs
			</div>
		</div>
	</div>

    <div class="col-md-6" id="adverts">
		
		@foreach ($adverts as $advert)
		<a href="adverts/{{ $advert->id }}/{{ $advert->job_title }}">
			<div class="panel panel-default">
			  	<div class="panel-heading">{{ $advert->job_title }}</div>
			  	<div class="panel-body">		
					<div class="salary">RM {{ $advert->salary }} per hour</div>
					<div class="business-name">{{ $advert->business_name }}</div>
					<div class="location">{{ $advert->location }}</div>
					<div class="street">{{ $advert->street }}</div>

				</div>
			</div>
		</a>
		@endforeach
			
	</div>

	<div class="col-md-3">
		<div class="panel panel-default">
			<!-- todo Jumpbar -->
			<div class="panel-body">
			Other things, i.e. adverts, announcements.
			</div>
		</div>
	</div>
</div>
@stop