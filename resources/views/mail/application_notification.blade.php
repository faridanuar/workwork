<html>
	<body>
		<div>
			<img alt="WorkWork" src="<?php echo $message->embed('images/workwork-mail-logo.png'); ?>">
		</div>
		<p></p>
		<div>
			You got a response from your JOB APPLICATION for <b>{{ $application->advert->job_title }}</b>
		</div>
		<p></p>
		<div>
			<b>Status:</b> {{ $application->status }}<br>
			<b>Message:</b> {{ $application->employer_comment }}
		</div>
		<p></p>
		<div>
			For more info: <a href="{{ $websiteURL }}my/applications/{{ $application->id }}">click here</a>
		</div>
	</body>
</html>
