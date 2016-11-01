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
        <div class="panel panel-default">
            <div class="panel-body">
                Notifications
                <div>
                @if($user->verified != 1)
                   You have not verified your email. Click <a href="/request/link"><b>Here</b></a>
                @endif

                @if($user->contact_verified != 1)
                   You have not verified your email. Click <a href="/contact/verification"><b>Here</b></a>
                @endif

                @if($user->verified != 1)
                   You have not verified your email. Click <a href="/send/link"><b>Here</b></a>
                @endif

                @can('edit_company')
                    @forelse( $noticeInfos as $noticeInfo )
                        {{ $message }} <b>{{ $noticeInfo->jobSeeker->user->name }}</b> for a job as <b>{{ $noticeInfo->advert->job_title }}</b> - 
                        <a href="/advert/{{ $noticeInfo->advert->id }}/job/requests/pending">View</a><br>
                    @empty
                        {{ $message1 }} <a href="{{ $link }}">{{ $message2 }}</a>
                    @endforelse
                @endcan

                @can('edit_info')
                    @forelse( $noticeInfos as $noticeInfo )
                        {{ $message }} <b>{{ $noticeInfo->advert->job_title }}</b> - 
                        <a href="/my/applications/{{ $noticeInfo->id }}">View</a><br>
                    @empty
                        {{ $message1 }} <a href="{{ $link }}">{{ $message2 }}</a>
                    @endforelse
                @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
