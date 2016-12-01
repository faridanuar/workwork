<html>
	<body>
		<div>
			<img alt="WorkWork" src="<?php echo $message->embed('images/workwork-mail-logo.png'); ?>">
		</div>
		<p></p>
		<div>
			<b>Thank you for registering in WorkWork</b>
		</div>
		<p></p>
		<div>
			Welcome Job Seeker!
		</div>
		<p></p>
		<div>
			Now all you need to do is verify your email and then you can get started! <br>
			Click here to verify: 
			<a class="btn btn-primary" href="{{ $website }}register/verify/{{ $verification_code }}">{{ $website }}register/verify/{{ $verification_code }}</a>
		</div>
		<p></p>
		<div>
			(If you did not perform this action then you do not have to do anything else)
		</div> 
	</body>
</html>