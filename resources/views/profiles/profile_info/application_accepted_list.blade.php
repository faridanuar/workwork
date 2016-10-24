@extends('layouts.app')

@section('content')

@include('profiles.profile_info.applications_category')

<div>
	<h3><u>Accepted List</u></h3>
	@foreach($acceptedInfos as $acceptedInfo)

		<div class="form-group">
			<a href="/my/applications/{{ $acceptedInfo->id }}">
			<div><h4>Status: {{ $acceptedInfo->status }}</h4></div>
			<div><h4>Job Request For: {{ $acceptedInfo->advert->job_title }}</h4></div>
			</a>
		</div>

	@endforeach
</div>

{!! $acceptedInfos->render() !!}

<script type="text/javascript">
$(document).ready(function(){
    $.ajax({
      type: "POST",
      url: "/set-as-viewed",
      context: document.body,
      data: {
            'viewed': 'accepted',
            '_token': '{!! csrf_token() !!}'
            }
    });
});
</script>

@stop