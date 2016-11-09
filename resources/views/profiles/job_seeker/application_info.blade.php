@extends('layouts.app')

@section('content')

<a href="/my/applications">Back</a>
<h2>{{$appInfo->advert->job_title}}</h2>
Status: {{$appInfo->status}}<br>
Comment: {{$appInfo->employer_comment}}<br><br>

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