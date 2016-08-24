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
                    <img src="{{ $photo }}" height="100" width="100"/>
                    You are logged in!
                </div>
            </div>
            @can('edit_company')
                    <b>Status: {{ $subscription }}</b><br>
                @if($role->id)
                    <a href="/company/{{ $role->id }}/{{ $role->business_name }}" class="btn btn-primary">Company Profile</a>
                @endif
            @endcan

            @can('view_my_adverts')
            <a href="/my/adverts" class="btn btn-primary">View Adverts</a>
            @endcan

            @can('edit_info')
                    <a href="/my/applications" class="btn btn-primary">My Applications</a>
                @if($role->id)
                    <a href="/profile/{{ $role->id }}" class="btn btn-primary">Profile Info</a>
                @endif
            @endcan

            <a href="/avatar" class="btn btn-primary">Upload Avatar</a>
        </div>
    </div>
</div>
@endsection
