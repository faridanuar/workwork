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
                    <img src="{{ Auth::user()->avatar }}" height="100" width="100" />
                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
