@extends('layouts.app')
@section('content')
<div class="flash">
    @include('messages.flash')
</div>

<div class="panel-ww-600 panel-default center-block">
	<div class="panel panel-default">
		<div class="panel-body">
	
			<div class="form-group">
				Edit Account
			</div>

			<hr>

			<form method="post" action="/account/update" name="myForm">
				{!! csrf_field() !!}
				<div class="form-group">
					Email: {{ $user->email }}
				</div>
				
				<div class="form-group">
					Name:
					<input type="text" class="form-control" name="name" value="{{ $user->name }}" />
				</div>
				@if ($errors->has('name'))
					<div class="alert alert-danger">
				        {{ $errors->first('name') }}
				    </div>
	            @endif
				<div class="form-group">
					Contact: 
					<input type="number" class="form-control" name="contact" value="{{ $user->contact }}" />
				</div>
				@if ($errors->has('contact'))
					<div class="alert alert-danger">
		              	{{ $errors->first('contact') }}
		            </div>
	            @endif
	            <input type="submit" class="btn btn-default btn-sm" id="submitBtn" name="submitBtn" value="Save & Update" />
			</form>
		</div>
	</div>
</div>
@stop