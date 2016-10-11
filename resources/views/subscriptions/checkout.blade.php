@extends('layouts.app')

@section('content')
<div class="flash">
    @include('messages.flash')
</div>

@if($user->ftu_level < 4)
	@include('messages.ftu_level')
@else
	@include('messages.advert_level')
@endif

<div class="info">
	<h2>Use payment method with <u>Credit Card</u> or <u>PayPal</u></h2>
</div>

<div class="checkout">
	<form id="checkout" method="post" action="/process/{{$id}}">
	{!! csrf_field() !!}

		<div id="payment-form"></div>

		<div id="plan"></div>

		<input type="hidden" name="plan" id="plan" value="{{ $plan }}">

		<input type="submit" class="btn btn-primary" value="Next : Complete Checkout" id="submitBtn" onclick="restrict()">

	</form>
</div>

<script>
var clientToken = "{{ Braintree_ClientToken::generate() }}";

braintree.setup(clientToken, "dropin", {
  container: "payment-form"
});

function restrict() {
    document.getElementById("submitBtn").disabled = true;
    document.getElementById("checkout").submit();
}
</script>
@stop