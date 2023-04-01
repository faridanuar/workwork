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
            @if ($user->employer):
                <div class="panel-body">
                        <a href="/a/advert/create" class="btn btn-default btn-sm">Create New Advert</a>
                        <a href="/a/company/{{ $user->employer->id }}/{{ $user->employer->business_name }}" class="btn btn-default btn-sm">profile</a>
                        <a href="/a/activity/history" class="btn btn-default btn-sm">History</a>
                </div>
            @endif
        </div>

        <div class="form-group">
            <div>
                List of Created Adverts <span class="badge">{{ count($adverts) }}</span>
            </div>

            <p></p>

            @if ($adverts):
                @forelse( $adverts as $advert )
                    <div class="panel panel-default">
                        <div class="panel-body">
                            {{ $advert->job_title }} - {{ $advert->advert_from }} -
                            
                            @if($advert->published != 0)
                                <span class="label label-success label">PUBLISHED</span> 
                            @else
                                <span class="label label-warning">UNPUBLISHED</span> 
                            @endif

                            <p></p>

                            <a class="btn btn-default btn-sm" href="/adverts/{{ $advert->id }}/{{ $advert->job_title }}">View</a> 
                            <a class="btn btn-default btn-sm" href="/a/advert/{{ $advert->id }}/{{ $advert->job_title }}/logo/upload">Edit Logo</a> 
                            <a class="btn btn-default btn-sm" href="/a/advert/{{ $advert->id }}/{{ $advert->job_title }}/change/owner">Change Owner</a>
                            <a class="btn btn-default btn-sm" href="/a/advert/{{ $advert->id }}/{{ $advert->job_title }}/requests/all">Job Requests</a>
                            @if(count($advert->applications->where('responded', 0)) > 0)
                                <span class="badge">{{ count($advert->applications) }} New Request</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="form-group">
                        No available adverts...
                    </div>
                @endforelse
                <center>{{ $adverts->links() }}</center>
            @endif
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
