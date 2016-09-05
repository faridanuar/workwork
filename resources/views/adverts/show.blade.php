@extends('layouts.app')
@section('body-id', 'advert-view')
@section('content')

<div class="row">
    <div class="col-md-8">
        <div class="flash">
        	@include('messages.flash')
        </div>

        <div id="advert" class="panel panel-default">
            <div class="panel-body">
                <h1 class="job-title">{{ $advert->job_title }}</h1>
                <div class="salary">
                    <div class="amount"><sup class="currency">RM</sup>{{ $advert->salary }} </div>
                    <div class="rate"><span class="per">per</span> <span>{{ $advert->rate }}</span></div>
                </div>
                <hr>
                <div class="business-name"><a href="/company/{{ $advert->employer_id }}/{{ $advert->business_name }}">{{ $advert->business_name }}</a></div>
                <hr>
                <div class="category">
                    <h3>Category</h3>
                    <span class="label label-default">{{ $advert->category }}</span>
                </div>
                <div class="skill">
                    <h3>Skills</h3>
                    <span class="label label-default">{{ $advert->skill }}</span>
                </div>
                <div class="oku">
                    <h3>Suitable for</h3>
                    @if (str_is('yes',$advert->oku_friendly))
                    <span class="label label-default">OKU</span>
                    @endif
                </div>
                <div class="location">
                    <h3>Location</h3>
                    {{ $advert->location }}
                </div>
                <div class="schedule">
                    <h3>Work Schdule</h3>
                    July-August<br>
                    {{ $advert->schedule }}
                </div>
                <hr>
                <div class="description">
                    <h3>Work Description</h3>
                    {!! nl2br(e($advert->description)) !!}
                </div>
                <hr>
                <div class="business-name"><a href="/company/{{ $advert->employer_id }}/{{ $advert->business_name }}">{{ $advert->business_name }}</a></div>
                <div class="location">{{ $advert->location }}</div>
                <div class="street">{{ $advert->street }}</div>
                <div class="city">{{ $advert->city }}</div>
                <div class="zip">{{ $advert->zip }}</div>
                <div class="state">{{ $advert->state }}</div>
                <hr>

                @if ($authorize === true)

                	@can('edit_advert')
                    	<a href="/adverts/{{ $advert->id }}/{{ strtolower($advert->job_title) }}/edit" class="btn btn-primary">edit</a>
                        @if($advert->open === 0)
                            <form method="post" action="/adverts/publish">
                                {{ csrf_field() }}
                                <input type="hidden" id="id" name="id" value="{{ $advert->id }}" />
                                <button type="submit" class="btn btn-primary">Publish</button>
                            </form>
                        @else
                            <form method="post" action="/adverts/unpublish">
                                {{ csrf_field() }}
                                <input type="hidden" id="id" name="id" value="{{ $advert->id }}" />
                                <button type="submit" class="btn btn-primary">Unpublish</button>
                            </form>
                        @endif
                	@endcan

                @elseif ( $asEmployer === false )

                	<a href="/adverts/{{ $advert->id }}/{{ strtolower($advert->job_title) }}/apply" class="btn btn-primary btn-lg btn-block btn-ww-lg">Apply</a>

                @endif
            </div>
        </div>
    </div>
</div>
@stop