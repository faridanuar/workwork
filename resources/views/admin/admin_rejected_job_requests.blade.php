@extends('layouts.app')

@section('content')

@include('admin.admin_requests_category')

<div>Rejected Job Seekers:</div>

@forelse($rejectedInfos as $rejectedInfo)
	<a href="/a/advert/{{ $rejectedInfo->advert_id }}/job/requests/{{ $rejectedInfo->id }}">
		<div class="panel panel-default">
			<div class="panel-body">
				<div>
					Status: {{ $rejectedInfo->status }}
				</div>

				<hr>
				
				<div>
					Name: {{ $rejectedInfo->jobSeeker->user->name }}
				</div>
				<div>
					Age: {{ $rejectedInfo->jobSeeker->age }}
				</div>
				<div>
					Location: {{ $rejectedInfo->jobSeeker->location }}
				</div>
			</div>
		</div>
	</a>
@empty
	<p>Looks like there's no job applications yet.</p>
@endforelse</p>
	{!! $rejectedInfos->render() !!}
@stop