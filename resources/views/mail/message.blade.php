You got a job request from <b>{{ $user->name }}</b> <br><br>
<b>Introduction:</b> {{ $application->introduction }} <br><br>
<b>Age:</b> {{ $thisJobSeeker->age }} <br>
<b>Contact No. :</b> {{ $user->contact }} <br>
<b>Location:</b> {{ $thisJobSeeker->location }} <br>
<b>Street:</b> {{ $thisJobSeeker->street }} <br>
<b>City:</b> {{ $thisJobSeeker->city }} <br>
<b>Zipcode:</b> {{ $thisJobSeeker->zip }} <br>
<b>State:</b> {{ $thisJobSeeker->state }} <br>
<b>Country:</b> {{ $thisJobSeeker->country }} <br><br>

For more info: <a href="http://workwork.app/advert/{{ $advert->id }}/job/requests/pending">click here</a>
<br><br>
