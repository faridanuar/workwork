@extends('layouts.app')

@section('content')
<div class="flash">
    @include('messages.flash')
</div>

<div class="row">
    <div class="col-sm-3 col-md-2">
        <img src="{{ $avatar }}" class="img-responsive img-thumbnail" />
        <a href="/avatar" class="btn btn-link btn-block btn-sm">Change your avatar</a>
    </div>
    <div class="col-sm-9 col-md-8">
        <h3 class="hidden-xs">Hi, {{ $user->name }}</h3>
        <div class="panel panel-default">
            <div class="panel-body">
                    <a href="/a/advert/create" class="btn btn-default btn-sm">Create New Advert</a>
                    <a href="/a/activity/history" class="btn btn-default btn-sm">History</a>
            </div>
        </div>

        <div class="form-group">
           List of Created Adverts <span class="badge">0</span>
        </div>

        <div>
            @if($user->email)
                @if($user->verified != 1)
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <span class="label label-info">INFO</span>
                            You have not verified your email. 
                            <a href="/request/verification"><b>Click Here</b></a>
                        </div>
                    </div>
                @endif
            @endif

            @if($user->contact)
                @if($user->contact_verified != 1)
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <span class="label label-info">INFO</span>
                            You have not verified your contact number. 
                            <a href="/contact/verification"><b>Click Here</b></a>
                        </div>
                    </div>
                @endif
            @endif

           {{-- @can('view_admin_features')
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
            @endcan --}}
        </div>
    </div>
</div>
@endsection
