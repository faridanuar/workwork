@extends('layouts.app')

@section('meta_info')
    <meta property="og:title" content="{{ $advert->job_title }} - {{ $advert->employer->business_name }}" />
    <meta property="og:description" content="{!! nl2br(e($advert->description)) !!}" />
    <meta property="og:url" content="{{ $url }}" />
    <meta property="og:image" content="http://www.workwork.my/{{ $photo }}" />
    <meta property="og:image:type" content="image/{{ $extension }}" />
@stop

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
                        @if($asAdmin === true)

                            <a href="#">
                                <img src="{{ $advert->logo_from }}" class="media-object avatar-tn img-circle" height="30" width="30" />
                            </a>

                        @else

                            <a href="#">
                                <img src="{{ $photo }}" class="media-object avatar-tn img-circle" height="30" width="30" />
                            </a>

                        @endif
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">
                        <div class="business-name">
                            @if($asAdmin === true)

                                <small>Advert by:</small>
                                {{ $advert->advert_from }}

                            @else

                                <small>Posted by:</small>
                                <a href="/company/{{ $advert->employer_id }}/{{ $advert->employer->business_name }}">{{ $advert->employer->business_name }}</a>

                            @endif
                        </div>
                        </h4>
                    </div>
                </div>

                <hr>

                <div class="category visible-xs-block">
                    <h3>Share with friends </h3>
                    <div class="addthis_inline_share_toolbox"></div>

                    <h3>Category</h3>
                    <span class="label label-default">{{ $advert->category }}</span>
                </div>

                <div class="skill visible-xs-block">
                    <h3>Skills</h3>
                    {{ $skills }}
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

                <div class="schedule">
                    <h3>Work Schedule</h3>

                    @if($advert->schedule_type === 'specific')

                        <div>Specific</div>
                        <div>{{ $advert->specificSchedule->start_date }} - {{ $advert->specificSchedule->end_date }}</div>
                        <div>{{ $advert->specificSchedule->start_time }} - {{ $advert->specificSchedule->end_time }}</div>

                    @elseif($advert->schedule_type === 'daily')

                        <div><u>Daily</u></div>

                        @foreach($advert->dailySchedule as $day)
                            <div>{{ $day->day }} : {{ $day->pivot->start_time }} - {{ $day->pivot->end_time }}</div>
                        @endforeach

                        <div><u>Duration</u></div>
                        <div>Starts: {{ $advert->daily_start_date }} - Ends: {{ $advert->daily_end_date }}</div>

                    @else

                        <div>No Schedule</div>

                    @endif
                </div>

                <div class="description">
                    <h3>Work Description</h3>
                    {!! nl2br(e($advert->description)) !!}
                </div>

                <div class="business-name visible-xs-block">
                    <hr>
                    <h3>Company</h3>

                    @if($asAdmin === true)

                        {{ $advert->advert_from }}

                    @else
                        
                        <a href="/company/{{ $advert->employer_id }}/{{ $advert->employer->business_name }}">{{ $advert->employer->business_name }}</a>

                    @endif

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
                    @if($authorize === true)

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

                    @elseif($asEmployer === false)

                    	<a href="/adverts/{{ $advert->id }}/{{ strtolower($advert->job_title) }}/apply" class="btn btn-primary btn-lg btn-block btn-ww-lg">Apply</a>

                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4 hidden-xs hidden-print">
        <div class="panel panel-default" id="advertInfo">
            <div class="panel-body">
                <h3>Share with friends </h3>
                <div class="addthis_inline_share_toolbox"></div>
                <div class="category">
                    <h3>Category</h3>
                    <span class="label label-default">{{ $advert->category }}</span>
                </div>
                <div class="skill">
                    <h3>Skills</h3>
                    <div class="skill-description">
                        {{ $skills }}
                    </div>
                </div>
                <div class="location">
                    <h3>Location</h3>
                    {{ $advert->location }}
                </div>
                <hr>
                <div>
                    <h3>Company</h3>
                    
                    @if($asAdmin === true)

                        {{ $advert->advert_from }}

                    @else
                        
                        <a href="/company/{{ $advert->employer_id }}/{{ $advert->employer->business_name }}">{{ $advert->employer->business_name }}</a>

                    @endif
                    
                </div>
                <div class="address">
                    <div class="street">{{ $advert->street }}</div>
                    <div class="city">{{ $advert->city }}</div>
                    <div class="zip">{{ $advert->zip }}</div>
                    <div class="state">{{ $advert->state }}</div>
                </div>
                <hr>
                <div class="actions">
                    @if($authorize === true)

                        @can('edit_advert')
                            <a href="/adverts/{{ $advert->id }}/{{ strtolower($advert->job_title) }}/edit" class="btn btn-link">Edit</a>

                            @if($advert->published === 0)

                                <form method="post" action="/adverts/publish">
                                    {{ csrf_field() }}
                                    <input type="hidden" id="id" name="id" value="{{ $advert->id }}" />
                                    <button type="submit" class="btn btn-default">Publish</button>
                                </form>

                            @else

                                <form method="post" action="/adverts/unpublish">
                                    {{ csrf_field() }}
                                    <input type="hidden" id="id" name="id" value="{{ $advert->id }}" />
                                    <button type="submit" class="btn btn-default">Unpublish</button>
                                </form>

                            @endif
                        @endcan

                    @elseif( $asEmployer === false )

                        <a href="/adverts/{{ $advert->id }}/{{ strtolower($advert->job_title) }}/apply" class="btn btn-primary btn-lg btn-block btn-ww-lg">Apply</a>

                    @endif

                    @can('view_admin_features')
                        @if($authorize === true)

                            <a href="/a/advert/{{ $advert->id }}/{{ strtolower($advert->job_title) }}/edit" class="btn btn-link">Edit</a>

                            @if($advert->published === 0)

                                <form method="post" action="/a/advert/publish">
                                    {{ csrf_field() }}
                                    <input type="hidden" id="id" name="id" value="{{ $advert->id }}" />
                                    <button type="submit" class="btn btn-default">Publish</button>
                                </form>

                            @else

                                <form method="post" action="/a/advert/unpublish">
                                    {{ csrf_field() }}
                                    <input type="hidden" id="id" name="id" value="{{ $advert->id }}" />
                                    <button type="submit" class="btn btn-default">Unpublish</button>
                                </form>

                            @endif

                        @endif
                    @endcan

                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js_plugins')
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5876f7c1f3ac37d4"></script> 
@stop