@extends('layouts.app')

@section('content')
<h2>Here are some Jobs related to your job interest</h2>
@foreach($adverts as $advert)
<a class="panel-job-links" href="/adverts/{{ $advert->id }}/{{ $advert->job_title }}">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="business-name">{{ $advert->business_name }}</div>
                <div class="job-title">{{ $advert->job_title }}</div>
                <div class="salary"><div class="amount"><sup class="currency">RM</sup>{{ $advert->salary }} </div>
                    <div class="rate"> <span class="per">per</span> <span>{{ $advert->rate }}</span></div> </div>
                <div class="location hidden">{{ $advert->location }}</div>
                <div class="street hidden">{{ $advert->street }}</div>
                <div class="skill hidden">Skill: {{ $advert->skill }}</div>
        <img src="{{ $advert->avatar }}" class="avatar-tn" height="30" width="30" />
            </div>
        </div>
    </a>
@endforeach
@stop