@extends('layouts.app')
@section('content')

<div class="flash">
    @include('messages.flash')
</div>

<div class="panel-ww-600 panel-default center-block">
	<div class="panel panel-default">
		<div class="panel-body">

			<div>
				<form id="contactForm">
					Your Contact Number: 
					<input 
						type="number" 
						id="contact" 
						name="contact"
						class=""
						placeholder="Example: 017123456" 
						@if(!$contact)
							value = ""
						@else
							value = "{{ $contact }}"
						@endif
						disabled
					/>
					<input type="button" id="editContact" class="btn btn-link"  value="Edit" />
					<input type="button" id="saveStatus" class="hidden" value="" disabled />
				</form>
				<p></p>
				<div>(Avoid adding special characters such as "+6" or "-")</div>
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
					<input type="submit" id="verify" class="btn btn-primary"  value="VERIFY" disabled />
					<input type="button" id="getCode" class="btn btn-link"  value="GET CODE" disabled />
				</div>
			</form>
		</div>
	</div>
</div>
@stop

@section('js_plugins')
<script type="text/javascript">
$(document).ready(function(){

	var contact = $("#contact").val();

	if(contact != "")
	{
		$("#getCode")
    	.val("GET CODE")
    	.attr('disabled', false); 

    	$("#verify")
		.attr('disabled', false);
	}

	$("#getCode").click(function(){

		var contact = $("#contact").val();

		$("#message").addClass('hidden');

		if(contact != "")
		{
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

			$("#verify")
			.attr('disabled', true);

			$("#editContact")
			.attr('disabled', true);

		    setTimeout(function(){
		    	$("#getCode")
		    	.val("GET CODE")
		    	.attr('disabled', false); 

		    	$("#verify")
				.attr('disabled', false);

				$("#editContact")
				.attr('disabled', false);

		    	$("#message").removeClass('hidden');
		    }, 
		    4000);

		}else{

			$("#saveStatus")
			.removeClass('hidden')
    		.addClass('btn btn-link')
    		.val("Please fill in your Contact Number!");
		}
	});

	$("#editContact").click(function(){

		var contact = $("#contact").val();

		$("#saveStatus")
		.removeClass('btn btn-link')
		.addClass('hidden')
		.val("")

		if($("#editContact").val() == "Save")
		{
			if(contact != "")
			{
				$.ajax({
			      type: "POST",
			      url: "/update/contact",
			      data: {
			            	'_token': '{!! csrf_token() !!}',
			            	'contact': contact
			            }
			    });

			    $("#editContact")
				.val("Saving...")
				.attr('disabled', true);

				$("#contact")
				.attr('disabled', true);

				$("#getCode")
				.attr('disabled', true);

				$("#verify")
				.attr('disabled', true);

				setTimeout(function(){
			    	$("#editContact")
			    	.val("Edit")
			    	.attr('disabled', false)

		    		$("#saveStatus")
		    		.removeClass('hidden')
		    		.addClass('btn btn-link')
		    		.val("Success!")

			    	setTimeout(function(){
			    	$("#saveStatus")
			    	.addClass('hidden')
			    	.val("")
			    	}, 
			    	3000);

			    	$("#getCode")
					.attr('disabled', false);

					$("#verify")
					.attr('disabled', false);
			    }, 
			    4000);

			}else{

				$("#saveStatus")
				.removeClass('hidden')
	    		.addClass('btn btn-link')
	    		.val("Please fill in your Contact Number!");
			}

		}else{

			$("#editContact")
			.val("Save");
			
			$("#contact")
			.attr('disabled', false);
		}
	});

});
</script>
@stop