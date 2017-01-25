@extends('layouts.app')

@section('content')

@include('admin.admin_requests_category')

<div>All Job Seekers:</div>

@forelse($allInfos as $allInfo)
	<a href="/a/advert/{{ $allInfo->advert_id }}/job/requests/{{ $allInfo->id }}">
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

@stop