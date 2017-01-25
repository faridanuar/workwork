@extends('layouts.app')

@section('content')

<div class="ftu-panel panel-ww-600 panel panel-default center-block">
    <div class="panel-heading panel-heading-ww">Job request for: {{$appInfo->advert_job_title}}</div>
      <div class="panel-body">
        <div class="form-group">
          <a href="/my/applications" class="btn btn-default btn-sm">Back</a>
        </div>
        <div>
          Company: 
          <a href="/company/{{$appInfo->employer->id}}/{{$appInfo->employer->business_name}}">{{$appInfo->employer->business_name}}</a>
        </div>

        <hr>

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
@stop

@section('js_plugins')
  <script type="text/javascript">
  var applicationID = '{{ $appInfo->id }}';
  var applicationStatus = '{{ $appInfo->employer_responded }}';

  $(document).ready(function(){
    if(applicationStatus != 0)
    {
      $.ajax({
        type: "POST",
        url: "/viewed",
        context: document.body,
        data: {
              'applicationID': applicationID,
              '_token': '{!! csrf_token() !!}'
              }
      });
    }
  });
  </script>
@stop