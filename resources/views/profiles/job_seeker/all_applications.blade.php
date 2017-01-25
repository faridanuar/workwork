@extends('layouts.app')

@section('content')

@include('profiles.job_seeker.applications_category')

<div>All</div>

@foreach($requestInfos as $requestInfo)
	<a href="/my/applications/{{ $requestInfo->id }}">
		<div class="panel panel-default">
			<div class="panel-body">
				<div>
					Job: {{ $requestInfo->advert_job_title }}
				</div>

				<hr>

				<div>
					Company: {{ $requestInfo->employer->business_name }}
				</div>

				<hr>

				<div>
					Status: {{ $requestInfo->status }}
				</div>
			</div>
		</div>
	</a>
@endforeach

{!! $requestInfos->render() !!}
@stop