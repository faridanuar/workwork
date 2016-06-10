@extends('layouts/app')

@section('content')


<h1>{{ $job->job_title }}</h1>

<hr>

<div class="salary">RM {{ $job->salary }}</div>
<div class="description">{!! nl2br(e($job->description)) !!}</div>
<div class="business-name">{{ $job->business_name }}</div>

<div class="location">{{ $job->location }}</div>
<div class="street">{{ $job->street }}</div>


@stop