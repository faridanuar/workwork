@extends('layouts.app')

@section('content')

<a href="/my/applications">Back</a>
<h2>{{$appInfo->advert->job_title}}</h2>
Status: {{$appInfo->status}}<br>
Comment: {{$appInfo->employer_comment}}<br><br>

<script type="text/javascript">

var status = '{{ $appInfo->status }}';

if(status === 'REJECTED')
{
  var request_status = 'rejected';
}else{
  var request_status = 'accepted';
}

$(document).ready(function(){
    $.ajax({
      type: "POST",
      url: "/viewed",
      context: document.body,
      data: {
            'viewed': request_status,
            '_token': '{!! csrf_token() !!}'
            }
    });
});
</script>

@stop