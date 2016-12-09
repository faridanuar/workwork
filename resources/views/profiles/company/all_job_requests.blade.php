@extends('layouts.app')

@section('content')

@include('profiles.company.requests_category')

<div>All Job Seekers:</div>

@forelse($allInfos as $allInfo)
	<a href="/advert/{{ $allInfo->advert_id }}/job/requests/{{ $allInfo->id }}">
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
					Location: {{ $allInfo->jobSeeker->location }}
				</div>
			</div>
		</div>
	</a>
@empty
	<p>Looks like there's no job applications yet.</p>
@endforelse
@if($currentPlan != "Trial")
	{!! $allInfos->render() !!}
@else
	If you want unlimited view for job requests, you must purchase the premium advert plan
@endif
@stop