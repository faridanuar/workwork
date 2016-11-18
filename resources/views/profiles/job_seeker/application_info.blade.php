@extends('layouts.app')

@section('content')

<div class="form-group">
	<a href="/my/applications" class="btn btn-default btn-sm">Back</a>
</div>

<div class="ftu-panel panel-ww-600 panel panel-default center-block">
    <div class="panel-heading panel-heading-ww">Job request for: {{$appInfo->advert->job_title}}</div>
      <div class="panel-body">
        <div>
          Status: {{$appInfo->status}}
        </div>

        <hr>

    		<div>
          Comment: {{$appInfo->employer_comment}}
        <div>
      </div>
	</div>
</div>

<script type="text/javascript">
var applicationID = '{{ $appInfo->id }}';

$(document).ready(function(){
    $.ajax({
      type: "POST",
      url: "/viewed",
      context: document.body,
      data: {
            'applicationID': applicationID,
            '_token': '{!! csrf_token() !!}'
            }
    });
});
</script>

@stop