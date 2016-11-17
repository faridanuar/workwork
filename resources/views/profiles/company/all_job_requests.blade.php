@extends('layouts.app')

@section('content')

@include('profiles.company.requests_category')

<div>Job Seekers:</div>

@forelse($allInfos as $allInfo)
	<a href="/advert/{{ $allInfo->advert_id }}/job/requests/{{ $allInfo->jobSeeker->id }}">
		<div class="panel panel-default">
			<div class="panel-body">
				<div>
					Status: {{ $allInfo->status }}
				</div>

				<hr>
				
				<div>
					Name: {{ $allInfo->jobSeeker->user->name }}
				</div>
				<div>
					Age: {{ $allInfo->jobSeeker->age }}
				</div>
				<div>
					Introduction: {{ $allInfo->introduction }}
				</div>
			</div>
		</div>
	</a>
@empty
	<p>Looks like there's no job applications yet.</p>
@endforelse</p>
@if($currentPlan != "Trial")
	{!! $allInfos->render() !!}
@else
	If you want unlimited view for job requests, you must purchase the premium advert plan
@endif
@stop