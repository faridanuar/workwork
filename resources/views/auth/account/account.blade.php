@extends('layouts.app')
@section('content')

<div class="flash">
	@include('messages.flash')
</div>

<div class="panel-ww-600 panel-default center-block">
	<div class="panel panel-default">
		<div class="panel-body">
			<div>
				Your Account
			</div>

			<hr>

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
				<a href="/account/edit" class="btn btn-default btn-sm">EDIT</a>
			</div>
		</div>
	</div>
</div>
@stop