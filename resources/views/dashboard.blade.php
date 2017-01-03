@extends('layouts.app')

@section('content')
<div class="flash">
    @include('messages.flash')
</div>

<div class="row">
    <div class="col-sm-3 col-md-2">
        <img src="{{ $photo }}" class="img-responsive img-thumbnail" />
        <a href="/avatar" class="btn btn-link btn-block btn-sm">Change your avatar</a>
    </div>
    <div class="col-sm-9 col-md-8">
        <h3 class="hidden-xs">Hi, {{ $user->name }}</h3>
        <!-- <div class="panel panel-default"> -->
            <!-- <div class="panel-body"> -->
                Notifications
                @if($user->verified != 1)
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <span class="label label-info">INFO</span>
                            You have not verified your email. 
                            <a href="/request/link">Click Here</a>
                        </div>
                    </div>
                @endif

                @if($user->contact_verified != 1)
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <span class="label label-info">INFO</span>
                            You have not verified your contact number. 
                            <a href="/contact/verification"><b>Click Here</b></a>
                        </div>
                    </div>
                @endif

                @if(!$user->email)
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <span class="label label-info">INFO</span>
                            It looks like you haven't added your email. 
                            <a href="/account/edit"><b>Add now</b></a>
                        </div>
                    </div>
                @endif

                @can('edit_company')
                    @forelse( $informations as $information )
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <span class="label label-info">INFO</span>
                                You have a request from {{ $information->jobSeeker->user->name }} for a job as "{{ $information->advert->job_title }}". 
                                <a href="/advert/{{ $information->advert->id }}/job/requests/{{ $information->id }}">
                                    <b>{{ $text }}</b>
                                </a>
                            </div>
                        </div>
                    @empty
                        @if($user->ftu_level < 4)
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <span class="label label-info">INFO</span>
                                    {{ $message1 }} 
                                    <a href="{{ $link }}">{{ $text1 }}</a>
                                </div>
                            </div>
                        @endif
                    @endforelse

                    @forelse( $level3Adverts as $level3Advert )
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <span class="label label-info">INFO</span>
                                You have not complete your checkout for your advert "{{ $level3Advert->job_title }}".
                                <a href="/choose/plan/{{ $level3Advert->id }}">
                                 <b>Continue</b>
                                </a>
                            </div>
                        </div>
                    @empty
                    @endforelse

                    @forelse( $adverts as $advert )
                        @if(Carbon\Carbon::now()->diffInDays($advert->plan_ends_at, false) > 0)
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <span class="label label-warning">WARNING</span>
                                    Your post for "{{ $advert->job_title }}" will expires in {{ Carbon\Carbon::now()->diffInDays($advert->plan_ends_at, false) }} days.
                                    <a href="/choose/plan/{{ $advert->id }}">
                                     <b>Extends</b>
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <span class="label label-info">INFO</span>
                                    Your post for "{{ $advert->job_title }}" has expired.
                                    <a href="/choose/plan/{{ $advert->id }}">
                                     <b>Extends</b>
                                    </a>
                                </div>
                            </div>
                        @endif
                    @empty
                    @endforelse
                @endcan

                @can('edit_info')
                    @forelse( $informations as $information )
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <span class="label label-info">INFO</span>
                                {{ $message }} "{{ $information->advert->job_title }}".
                                <a href="/my/applications/{{ $information->id }}"><b>View<b></a>
                            </div>
                        </div>
                    @empty
                        @if($user->ftu_level < 2)
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <span class="label label-info">INFO</span>
                                    {{ $message1 }} 
                                    <a href="{{ $link }}"><b>{{ $text1 }}<b></a>
                                </div>
                            </div>
                        @endif
                    @endforelse
                @endcan
            <!-- </div> -->
       <!-- </div> -->
    </div>
</div>
@endsection
