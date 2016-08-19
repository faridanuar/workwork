@extends('layouts.app')

@section('content')

<div class="flash">
	@include('messages.flash')
</div>


<h1>{{ $advert->job_title }}</h1>

<hr>

<div class="salary">RM {{ $advert->salary }} Per {{ $advert->rate }}</div>
<div class="description">{!! nl2br(e($advert->description)) !!}</div>
<div class="business-name"><a href="/company/{{ $advert->employer_id }}/{{ $advert->business_name }}">{{ $advert->business_name }}</a></div>
<div class="location">{{ $advert->location }}</div>
<div class="street">{{ $advert->street }}</div>
<div class="city">{{ $advert->city }}</div>
<div class="zip">{{ $advert->zip }}</div>
<div class="state">{{ $advert->state }}</div>
<div class="skill">{{ $advert->skill }}</div>
<div class="oku">Also suitable for OKU?: {{ $advert->oku_friendly }}</div>

<hr>

@if ($authorize === true)

	@can('edit_advert')

		<a href="/adverts/{{ $advert->id }}/{{ strtolower($advert->job_title) }}/edit" class="btn btn-primary">edit</a>

	@endcan

@elseif ( $asEmployer === false )

	<a href="/adverts/{{ $advert->id }}/{{ strtolower($advert->job_title) }}/apply" class="btn btn-primary">Apply</a>

@endif

@stop