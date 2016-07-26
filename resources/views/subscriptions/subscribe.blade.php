@extends('layouts.app')

@section('content')

<h2>Subscribe now with Credit Card or PayPal</h2>

<form id="checkout" method="post" action="checkout">
{{ csrf_field() }}
  <div id="payment-form"></div>

	<div class="form-group">
		<h4>Select a plan:</h4>
		  <select name="plan" id="plan" class="form-control" required>
		  <option value="" selected disabled>Select a plan...</option>
		  <option value="1week" >BANGLONG PLAN</option>
		  <option value="1month" >TAUKE PLAN</option>
		  <option value="2month" >GODFATHER PLAN</option>
		  </select>
	 </div>

  <input type="submit" class="btn btn-primary value="Pay $10">
</form>

<script src="https://js.braintreegateway.com/js/braintree-2.27.0.min.js"></script>

<script>

var clientToken = "{{ Braintree_ClientToken::generate() }}";

braintree.setup(clientToken, "dropin", {
  container: "payment-form"
});


</script>

@stop