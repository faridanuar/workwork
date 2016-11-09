@extends('layouts.app')
@section('content')

<div class="panel-ww-600 panel-default center-block">

	<div class="flash">
	    @include('messages.flash')
	</div>

	<div><h3>Your Account</h3></div>

	<div>Email: {{ $user->email }}</div>
	<div>Name: {{ $user->name }}</div>
	<div>Contact: {{ $user->contact }}</div>
	<a href="/account/edit" class="btn btn-default btn-sm">EDIT</a>
</div>
@stop