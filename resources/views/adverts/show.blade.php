@extends('layouts.app')

@section('content')


<h1>{{ $advert->job_title }}</h1>

<hr>

<div class="salary">RM {{ $advert->salary }}</div>
<div class="description">{!! nl2br(e($advert->description)) !!}</div>
<div class="business-name">{{ $advert->business_name }}</div>

<div class="location">{{ $advert->location }}</div>
<div class="street">{{ $advert->street }}</div>

<hr>

@if ($authorize === true)

	@can('edit_advert')

		<a href="/adverts/{{ $advert->id }}/{{ strtolower(str_replace(' ','-',$advert->job_title)) }}/edit" class="btn btn-primary">edit</a>

	@endcan

@else

	<a href="/adverts/{{ $advert->id }}/{{ strtolower(str_replace(' ','-',$advert->job_title)) }}/apply" class="btn btn-primary">Apply</a>

@endif

@stop