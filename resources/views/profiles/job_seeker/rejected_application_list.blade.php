@extends('layouts.app')

@section('content')

@include('profiles.job_seeker.applications_category')

<div>Rejected List</div>

@foreach($rejectedInfos as $rejectedInfo)
	<a href="/my/applications/{{ $rejectedInfo->id }}">
		<div class="panel panel-default">
			<div class="panel-body">
				<div>
					Job: {{ $rejectedInfo->advert->job_title }}
				</div>

				<hr>

				<div>
					Company: {{ $rejectedInfo->employer->business_name }}
				</div>

				<hr>

				<div>
					Status: {{ $rejectedInfo->status }}
				</div>
			</div>
		</div>
	</a>
@endforeach

{!! $rejectedInfos->render() !!}
@stop

@section('js_plugins')
	<script type="text/javascript">
	$(document).ready(function(){
	    $.ajax({
	      type: "POST",
	      url: "/category-viewed",
	      context: document.body,
	      data: {
	            'viewed': 'rejected',
	            '_token': '{!! csrf_token() !!}'
	            }
	    });
	});
	</script>
@stop