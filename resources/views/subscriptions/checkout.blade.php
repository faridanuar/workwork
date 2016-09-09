@extends('layouts.app')

@section('content')
<div class="flash">
    @include('messages.flash')
</div>

<div class="info">
<h2>Use payment method with <u>Credit Card</u> or <u>PayPal</u></h2>
</div>

<div class="checkout">
	<form id="checkout" method="post" action="/process/{{$id}}">
	{!! csrf_field() !!}

		<div id="payment-form"></div>

		<div id="plan"></div>

		<input type="hidden" name="plan" id="plan" value="{{ $plan }}">

		<input type="submit" class="btn btn-primary" value="Next : Complete Checkout">

	</form>
</div>

<script src="https://js.braintreegateway.com/js/braintree-2.27.0.min.js"></script>

<script>

var clientToken = "{{ Braintree_ClientToken::generate() }}";

braintree.setup(clientToken, "dropin", {
  container: "payment-form"
});


</script>

@stop