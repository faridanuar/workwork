<html>
	<body>
		<div>
			<img alt="WorkWork" src="<?php echo $message->embed('images/workwork-mail-logo.png'); ?>">
		</div>
		<p></p>
		<div>
			Your post for <b>{{ $advert->job_title }}</b> is about to expire.
		</div>
		<p></p>
		<div>
			If you want to extend your post subscription. Please choose a plan by clicking on the provided link: <a href="{{ $website }}choose/plan/{{ $advert->id }}">Extend</a>
		</div>
	</body>
</html>