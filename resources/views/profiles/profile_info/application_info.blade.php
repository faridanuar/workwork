@extends('layouts.app')

@section('content')

<a href="/my/applications">Back</a>
<h2>{{$appInfo->advert->job_title}}</h2>
Status: {{$appInfo->status}}<br>
Comment: {{$appInfo->employer_comment}}<br><br>

@stop