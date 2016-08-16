@extends('layouts.app')

@section('content')
<div class="flash">
    @include('messages.flash')
</div>

<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <img src="{{ $user->avatar }}" height="100" width="100" onerror="imgError(this);"/>
                    You are logged in!
                </div>
            </div>
            @can('edit_company')
                @if($role->id)
                    <a href="/company/{{ $role->id }}/{{ $role->business_name }}" class="btn btn-primary">Company Profile</a>
                @else
                    <a href="/company/create" class="btn btn-primary">Create Profile</a>
                @endif
            @endcan

            @can('view_my_adverts')
            <a href="/my/adverts" class="btn btn-primary">View Adverts</a>
            @endcan

            @can('edit_info')
                @if($role->id)
                    <a href="/profile/{{ $role->id }}/{{ $role->user_id }}" class="btn btn-primary">Profile Info</a>
                @else
                    <a href="/profile/create" class="btn btn-primary">Create Profile</a>
                @endif
            @endcan

            <a href="/avatar" class="btn btn-primary">Upload Avatar</a>
        </div>
    </div>
</div>

@include('java_plugins.defaultPhoto')
@endsection
