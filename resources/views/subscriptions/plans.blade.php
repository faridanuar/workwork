@extends('layouts.app')

@section('content')

<div class="row">

<h1><u>Free Trial Plan info</u></h1>
<ul class="plan">
	<li><h1>Banglong Plan (Free Trial)</h1><h3>Wanna try it out first? register for a free trial and advertise job posts for a whole week</h3><a href="/register" class="btn btn-primary">Try it now</a></li>
</ul>

<h1><u>Paying Plans Info</u></h1>

<ul class="plan">
	<li><h1>Tauke Plan</h1><h3>Advertise unlimited job posts for a whole month!</h3></li>
	

	<li><h1>Godfather Plan</h1><h3>Advertise unlimited job posts for 2 months!</h3></li>
	

	<li><h1>Pioneer Promo Plan! (Limited time)</h1><h3>Advertise unlimited job posts for a whole month and pay less!</h3><a href="/subscribe" class="btn btn-primary">Choose a Plan</a></li>
</ul>

</div>
@stop