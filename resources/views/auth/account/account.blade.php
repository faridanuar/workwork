@extends('layouts.app')
@section('content')

<div class="flash">
	@include('messages.flash')
</div>

<div class="ftu-panel panel-ww-600 panel panel-default center-block">
    <div class="panel-heading panel-heading-ww">Your Account</div>
		<div class="panel-body">
			<div>
				Email: {{ $user->email }}
			</div>
			<div>
				Name: {{ $user->name }}
			</div>
			<div>
				Contact: {{ $user->contact }}
			</div>

			<p></p>
			
			<div>
				<a href="/account/edit" class="btn btn-default btn-sm">Edit</a>
			</div>
		</div>
</div>
@stop