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
        <h3 class="hidden-xs">Hi, {{ Auth::user()->name }}</h3>
        <div class="panel panel-default">
            <div class="panel-body">
                Notifications
                <div>
                    @if($user->type === "employer")
                        @if($requestTotal > 0)
                            You have {{ $requestTotal }} request! - <a href="/adverts">View</a>
                        @endif
                    @else
                        @if($responseTotal > 0)
                            You have {{ $responseTotal }} response! - <a href="/my/applications">View</a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
