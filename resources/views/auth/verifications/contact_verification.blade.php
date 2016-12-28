@extends('layouts.app')
@section('content')

<div class="flash">
    @include('messages.flash')
</div>

<div class="panel-ww-600 panel-default center-block">
	<div class="panel panel-default">
		<div class="panel-body">

			<div>
				Your Contact Number: 
					@if(!$contact)
						None
					@else
						{{ $contact }}
					@endif
				<a href="/account/edit" class="btn btn-default btn-sm">Edit</a>
				<p></p>
				<div>- Example: 017123456 -</div>
			</div>

			<hr>
			
			<p>
				<div id="message" class="hidden">
	            	Info: Your code has been sent to your phone. If you did not recieve any code please try again
	            </div>
            </p>


			<div>
				Enter code here:
			</div>

			<p></p>

			<form method="post" action="/verify/contact" name="myForm">
				{!! csrf_field() !!}

				<div class="form-group md-6">
					<input type="number" name="code" id="code" class="form-control" required />
					@if ($errors->has('code'))
						<div class="alert alert-danger">
			                <strong>{{ $errors->first('code') }}</strong>
			            </div>
		            @endif
				</div>
				<div class="form-group">
					<input type="submit" class="btn btn-primary"  value="VERIFY" />
					<input type="button" id="getCode" class="btn btn-link"  value="GET CODE" />
				</div>
			</form>
		</div>
	</div>
</div>
@stop

@section('js_plugins')
<script type="text/javascript">
$(document).ready(function(){

	$("#getCode").click(function(){

		$("#message").addClass('hidden');

	    $.ajax({
	      type: "POST",
	      url: "/request/contact/code",
	      data: {
	            	'_token': '{!! csrf_token() !!}'
	            }
	    });

		$("#getCode")
		.val("SENDING...")
		.attr('disabled', true); 

	    setTimeout(function(){
	    	$("#getCode")
	    	.val("GET CODE")
	    	.attr('disabled', false); 

	    	$("#message").removeClass('hidden');
	    }, 
	    4000);
	});

});
</script>
@stop