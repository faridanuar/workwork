@extends('layouts.app')

@section('content')


<h1>{{ $job->job_title }}</h1>

<hr>

<div class="salary">RM {{ $job->salary }}</div>
<div class="description">{!! nl2br(e($job->description)) !!}</div>
<div class="business-name">{{ $job->business_name }}</div>

<div class="location">{{ $job->location }}</div>
<div class="street">{{ $job->street }}</div>

<hr>

@can('click_apply')

	<a href="/adverts/{{ $job->id }}/{{ strtolower(str_replace(' ','-',$job->job_title)) }}/apply" class="btn btn-primary">Apply</a>

@endcan

@stop