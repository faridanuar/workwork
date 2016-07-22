@extends('layouts.app')

@section('content')

<h2>Subscribe Now!</h2>

<form id="checkout" method="post" action="checkout">
{{ csrf_field() }}
  <div id="payment-form"></div>
  <input type="submit" value="Pay $10">
</form>

<script src="https://js.braintreegateway.com/js/braintree-2.27.0.min.js"></script>

<script>

var clientToken = 'sandbox_g42y39zw_348pk9cgf3bgyw2b';

braintree.setup(clientToken, "dropin", {
  container: "payment-form"
});


</script>

@stop