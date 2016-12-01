<html>
	<body>
		<div>
			<img alt="WorkWork" src="<?php echo $message->embed('images/workwork-mail-logo.png'); ?>">
		</div>
		<p></p>
		<div>
			You got a job request from <b>{{ $user->name }}</b>
			<p></p>
			<b>Introduction:</b> {{ $application->introduction }}
			<p></p>
			<b>Age:</b> {{ $thisJobSeeker->age }} <br>
			<b>Contact No. :</b> {{ $user->contact }} <br>
			<b>Location:</b> {{ $thisJobSeeker->location }} <br>
			<b>Street:</b> {{ $thisJobSeeker->street }} <br>
			<b>City:</b> {{ $thisJobSeeker->city }} <br>
			<b>Zipcode:</b> {{ $thisJobSeeker->zip }} <br>
			<b>State:</b> {{ $thisJobSeeker->state }} <br>
			<b>Country:</b> {{ $thisJobSeeker->country }}
		</div>
		<p></p>
		<div>
			For more info: <a href="{{ $websiteURL }}advert/{{ $advert->id }}/job/requests/pending">click here</a>
		</div>
	</body>
</html>