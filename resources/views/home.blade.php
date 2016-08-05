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
            <a href="/company/{{ $role->id }}/{{ $role->business_name }}" class="btn btn-primary">Company Profile</a>
            @endcan

            @can('edit_info')
            <a href="/profile/{{ $role->id }}/{{ $user->name }}" class="btn btn-primary">Profile Info</a>
            @endcan

            <a href="/avatar" class="btn btn-primary">Upload Avatar</a>
        </div>
    </div>
</div>

@include('javaPlugins.defaultPhoto')
@endsection
