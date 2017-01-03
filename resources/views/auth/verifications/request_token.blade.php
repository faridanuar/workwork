@extends('layouts.app')

@section('content')
<div class="flash">
    @include('messages.flash')
</div>

<div class="panel-ww-600 panel-default center-block">
	<div class="panel panel-default">
		<div class="panel-body">
	
			<div class="form-group">
				Request for email verification code
			</div>

			<hr>
				<div class="form-group">
					Email: {{ $user->email }}
				</div>

	            <a href="/send/verification" class="btn btn-default btn-sm">Send Request</a>
			</form>
		</div>
	</div>
</div>
@stop