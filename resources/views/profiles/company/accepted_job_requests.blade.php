@extends('layouts.app')

@section('content')

@include('profiles.company.requests_category')

<div>Job Seekers:</div>
@forelse($acceptedInfos as $acceptedInfo)
	<a href="/advert/{{ $acceptedInfo->advert_id }}/job/requests/{{ $acceptedInfo->id }}">
		<div class="panel panel-default">
			<div class="panel-body">
				<div>
					Status: {{ $acceptedInfo->status }}
				</div>

				<hr>
				
				<div>
					Name: {{ $acceptedInfo->jobSeeker->user->name }}
				</div>
				<div>
					Age: {{ $acceptedInfo->jobSeeker->age }}
				</div>
				<div>
					Location: {{ $acceptedInfo->jobSeeker->location }}
				</div>
			</div>
		</div>
	</a>
@empty
	<p>Looks like there's no job applications yet.</p>
@endforelse</p>
{!! $acceptedInfos->render() !!}
@stop