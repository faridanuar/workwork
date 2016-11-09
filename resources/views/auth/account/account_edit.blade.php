@extends('layouts.app')
@section('content')
<div class="panel-ww-600 panel-default center-block">
	<div class="flash">
	    @include('messages.flash')
	</div>

	<div class="form-group"><h3>Edit Account</h3></div>

	<form method="post" action="/account/update" name="myForm">
		{!! csrf_field() !!}
		<div class="form-group">
			Email: <b>{{ $user->email }}</b>
		</div>
		<div class="form-group">
			Name:
			<input type="text" class="form-control" name="name" value="{{ $user->name }}" />
		</div>
		
		<div class="form-group">
			Contact: 
			<input type="number" class="form-control" name="contact" value="{{ $user->contact }}" />
		</div>
		<a href="/dashboard" class="btn btn-primary">CANCEL</a>
		<input type="submit" class="btn btn-primary" id="submitBtn" name="submitBtn" value="Save & Update" />
	</form>
</div>
@stop