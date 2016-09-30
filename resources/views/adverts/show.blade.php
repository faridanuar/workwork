@extends('layouts.app')
@section('body-id', 'advert-view')
@section('content')

<div class="row" id="advert">
    <div class="col-sm-8">
        <div class="flash">
        	@include('messages.flash')
        </div>

        @if ($authorize === true)
            @if($user->ftu_level < 4)
                @include('messages.ftu_level')
            @elseif($advert->advert_level < 3)
                @include('messages.advert_level')
            @endif
        @endif

        <div class="panel panel-default">
            <div class="panel-body">
                <h1 class="job-title">{{ $advert->job_title }}</h1>
                <div class="salary">
                    <div class="amount"><sup class="currency">RM</sup>{{ $advert->salary }} </div>
                    <div class="rate"><span class="per">per</span> <span>{{ $advert->rate }}</span></div>
                </div>
                <hr>
                <div class="media">
                    <div class="media-left">
                        <a href="#">
                            <img src="{{ $advert->avatar }}" class="media-object avatar-tn img-circle" height="30" width="30" />
                        </a>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">
                        <div class="business-name">
                            <small>Posted by:</small>
                            <a href="/company/{{ $advert->employer_id }}/{{ $advert->business_name }}">{{ $advert->business_name }}</a>
                        </div>
                        </h4>
                    </div>
                </div>

                <hr>
                <div class="category visible-xs-block">
                    <h3>Category</h3>
                    <span class="label label-default">{{ $advert->category }}</span>
                </div>
                <div class="skill visible-xs-block">
                    <h3>Skills</h3>
                    <!-- <span class="label label-default">{{ $advert->skill }}</span> -->
                    <div class="skill-description">
                        {{ $advert->skill }}
                    </div>
                </div>

                <!--
                <div class="oku">
                    <h3>Suitable for</h3>
                    @if (str_is('yes',$advert->oku_friendly))
                    <span class="label label-default">OKU</span>
                    @endif
                </div>
                -->

                <div class="location visible-xs-block">
                    <h3>Location</h3>
                    {{ $advert->location }}
                    <hr>
                </div>

               <!--
                <div class="schedule">
                    <h3>Work Schdule</h3>
                    July-August<br>
                    {{ $advert->schedule }}
                </div>
                -->

                <div class="description">
                    <h3>Work Description</h3>
                    {!! nl2br(e($advert->description)) !!}
                </div>

                <div class="business-name visible-xs-block">
                    <hr>
                    <a href="/company/{{ $advert->employer_id }}/{{ $advert->business_name }}">{{ $advert->business_name }}</a>
                </div>
                <!-- <div class="location">{{ $advert->location }}</div> -->
                <div class="address visible-xs-block">
                    <div class="street">{{ $advert->street }}</div>
                    <div class="city">{{ $advert->city }}</div>
                    <div class="zip">{{ $advert->zip }}</div>
                    <div class="state">{{ $advert->state }}</div>
                    <hr>
                </div>

                <div class="visible-xs-block">
                    @if ($authorize === true)

                    	@can('edit_advert')
                        	<a href="/adverts/{{ $advert->id }}/{{ strtolower($advert->job_title) }}/edit" class="btn btn-default">Edit</a>
                            @if($advert->published === 0)
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
    <div class="col-sm-4 hidden-xs hidden-print">
        <div class="panel panel-default" id="advertInfo">
            <div class="panel-body">
                <div class="category">
                    <h3>Category</h3>
                    <span class="label label-default">{{ $advert->category }}</span>
                </div>
                <div class="skill">
                    <h3>Skills</h3>
                    <!-- <span class="label label-default">{{ $advert->skill }}</span> -->
                    <div class="skill-description">
                        {{ $advert->skill }}
                        @foreach($advert->skills as $talent)
                            {{$talent->skill}},
                        @endforeach
                    </div>
                </div>
                <div class="location">
                    <h3>Location</h3>
                    {{ $advert->location }}
                </div>
                <hr>
                <div>
                    <h3>Company</h3>
                    <a href="/company/{{ $advert->employer_id }}/{{ $advert->business_name }}">{{ $advert->business_name }}</a>
                </div>
                <div class="address">
                    <div class="street">{{ $advert->street }}</div>
                    <div class="city">{{ $advert->city }}</div>
                    <div class="zip">{{ $advert->zip }}</div>
                    <div class="state">{{ $advert->state }}</div>
                </div>
                <hr>
                <div class="actions">
                    @if ($authorize === true)
                        @can('edit_advert')
                            <a href="/adverts/{{ $advert->id }}/{{ strtolower($advert->job_title) }}/edit" class="btn btn-default">Edit</a>
                            @if($advert->published === 0)
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
</div>
@stop