@extends('layouts.app')

@section('content')

@include('profiles.job_seeker.applications_category')

<div>Accepted List</div>

@foreach($acceptedInfos as $acceptedInfo)
	<a href="/my/applications/{{ $acceptedInfo->id }}">
		<div class="panel panel-default">
			<div class="panel-body">
				<div>
					Job: {{ $acceptedInfo->advert->job_title }}
				</div>

				<hr>

				<div>
					Company: {{ $acceptedInfo->employer->business_name }}
				</div>

				<hr>

				<div>
					Status: {{ $acceptedInfo->status }}
				</div>
			</div>
		</div>
	</a>
@endforeach

{!! $acceptedInfos->render() !!}

<script type="text/javascript">
$(document).ready(function(){
    $.ajax({
      type: "POST",
      url: "/category-viewed",
      context: document.body,
      data: {
            'viewed': 'accepted',
            '_token': '{!! csrf_token() !!}'
            }
    });
});
</script>
@stop