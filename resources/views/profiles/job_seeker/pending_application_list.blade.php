@extends('layouts.app')

@section('content')

@include('profiles.job_seeker.applications_category')

<div>Pending List</div>

@foreach($requestInfos as $requestInfo)
	<a href="/my/applications/{{ $requestInfo->id }}">
		<div class="panel panel-default">
			<div class="panel-body">
				<div>
					Status: {{ $requestInfo->status }}
				</div>

				<hr>
				
				<div>
					Job Request For: {{ $requestInfo->advert->job_title }}
				</div>
			</div>
		</div>
	</a>
@endforeach

{!! $requestInfos->render() !!}
@stop