@extends('layouts.app')
@section('content')

<div class="panel-ww-600 panel-default center-block">

	<div class="flash">
	    @include('messages.flash')
	</div>

	<div><h3>
	Your Contact Number: 
	@if(!$contact)
		None
	@else
		{{ $contact }}
	@endif
	 - 
	<a href="/account/edit">Edit</a>
	</h3></div>

	<div>Enter code here:</div>
	<form method="post" action="/verify/contact" name="myForm">
		{!! csrf_field() !!}

		<div class="form-group md-6">
			<input type="number" name="code" id="code" class="form-control" required />
			@if ($errors->has('code'))
                    <strong>{{ $errors->first('code') }}</strong>
            @endif
		</div>
		<div class="form-group">
			<input type="submit" class="btn btn-primary"  value="VERIFY" />
			<input type="button" id="getCode" class="btn btn-primary"  value="GET CODE" />
			<div class="message">
            	<strong>Info: Your code has been sent to your phone. If you did not recieve any code please try again</strong>
            </div>
		</div>
	</form>
</div>

<script type="text/javascript">
$(document).ready(function(){

	$(".message").hide();

	$("#getCode").click(function(){

		$(".message").hide();

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

	    	$(".message").show();
	    }, 
	    4000);
	});

});
</script>
@stop