@extends('layouts.app')

@section('content')
<div class="flash">
    @include('messages.flash')
</div>

<div class="row">
    <div class="col-sm-3 col-md-2">

        <img src="{{ $photo }}" class="img-responsive img-thumbnail" />
        <a href="/avatar" class="btn btn-default btn-sm">Change your avatar</a>
        <h3>{{ Auth::user()->name }}</h3>
        <!-- @can('edit_company')
            @if($role->id)
                <a href="/company/{{ $role->id }}/{{ $role->business_name }}" class="btn btn-primary">Company Profile</a>
            @endif
        @endcan

        @can('view_my_adverts')
        <a href="/adverts" class="btn btn-primary">View Adverts</a>
        @endcan

        @can('edit_info')
                <a href="/my/applications" class="btn btn-primary">My Applications</a>
            @if($role->id)
                <a href="/profile/{{ $role->id }}" class="btn btn-primary">Profile Info</a>
            @endif
        @endcan

        <a href="/avatar" class="btn btn-primary">Upload Avatar</a> -->
    </div>
    <div class="col-sm-9 col-md-8">
        <div class="panel panel-default">
            <div class="panel-body">
                Notifications
            </div>
        </div>
    </div>
</div>

@endsection
