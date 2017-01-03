@extends('layouts.app')

@section('content')

<div class="panel-ww-600 panel-default center-block">
	<div class="panel panel-default">
		<div class="panel-body">
	
			<div class="form-group">
				Edit Account
			</div>

			<hr>

			<form method="post" action="/send/link" name="myForm">
				{!! csrf_field() !!}
				<div class="form-group">
					Email:
					<input type="email" class="form-control" name="email" value="{{ $user->email }}" />
				</div>
				@if ($errors->has('email'))
					<div class="alert alert-danger">
				        {{ $errors->first('email') }}
				    </div>
	            @endif
	            <input type="submit" class="btn btn-default btn-sm" id="submitBtn" name="submitBtn" value="Request" />
			</form>
		</div>
	</div>
</div>
@stop