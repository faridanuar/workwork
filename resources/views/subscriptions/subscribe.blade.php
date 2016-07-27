@extends('layouts.app')

@section('content')

<div class="checkout">
<h2>Use payment method with <u>Credit Card</u> or <u>PayPal</u></h2>
</div>

<div class="checkout">
	<form id="checkout" method="post" action="checkout">
	{{ csrf_field() }}
	  <div id="payment-form"></div>

		<div class="form-group">
			<h4>Select a plan:</h4>
			  <select name="plan" id="plan" class="form-control" required>
			  <option value="" selected disabled>Select a plan...</option>
			  <option value="1month" >TAUKE PLAN</option>
			  <option value="2month" >GODFATHER PLAN</option>
			  <option value="pioneerPromo" >PIONEER PROMO PLAN</option>
			  </select>
		 </div>

	  <input type="submit" class="btn btn-primary value="Pay $10">
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