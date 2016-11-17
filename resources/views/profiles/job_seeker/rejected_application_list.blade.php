@extends('layouts.app')

@section('content')

@include('profiles.job_seeker.applications_category')

<div>Rejected List</div>

@foreach($rejectedInfos as $rejectedInfo)
		<a href="/my/applications/{{ $rejectedInfo->id }}">
			<div class="panel panel-default">
				<div class="panel-body">
					<div>
						Status: {{ $rejectedInfo->status }}
					</div>

					<hr>
					
					<div>
						Job Request For: {{ $rejectedInfo->advert->job_title }}
					</div>
				</div>
			</div>
		</a>
@endforeach

{!! $rejectedInfos->render() !!}

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