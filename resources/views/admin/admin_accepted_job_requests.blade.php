@extends('layouts.app')

@section('content')

@include('admin.admin_requests_category')

<div>Accepted Job Seekers:</div>

@forelse($acceptedInfos as $acceptedInfo)
	<a href="/a/advert/{{ $acceptedInfo->advert_id }}/job/requests/{{ $acceptedInfo->id }}">
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