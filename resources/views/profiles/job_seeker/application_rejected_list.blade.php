@extends('layouts.app')

@section('content')

@include('profiles.job_seeker.applications_category')

<div>
	<h3><u>Rejected List</u></h3>
	@foreach($rejectedInfos as $rejectedInfo)

		<div class="form-group">
			<a href="/my/applications/{{ $rejectedInfo->id }}">
			<div><h4>Status: {{ $rejectedInfo->status }}</h4></div>
			<div><h4>Job Request For: {{ $rejectedInfo->advert->job_title }}</h4></div>
			</a>
		</div>

	@endforeach
</div>

{!! $rejectedInfos->render() !!}

<script type="text/javascript">
$(document).ready(function(){
    $.ajax({
      type: "POST",
      url: "/set-as-viewed",
      context: document.body,
      data: {
            'viewed': 'rejected',
            '_token': '{!! csrf_token() !!}'
            }
    });
});
</script>

@stop