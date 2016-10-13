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

<h1 class="ftu-intro">@lang('ftu.payment')</h1>
<div class="ftu-panel panel-ww-600 panel panel-default center-block">
    <div class="ftu-arrow"></div>
    <div class="panel-heading panel-heading-ww">@lang('forms.payment_title')</div>
    <div class="panel-body">
    	<form id="checkout" method="post" action="/process/{{$id}}">
    	{!! csrf_field() !!}

    		<div id="payment-form"></div>

    		<div id="plan"></div>

    		<input type="hidden" name="plan" id="plan" value="{{ $plan }}">

    		<input type="submit" class="btn btn-primary btn-lg btn-block btn-ww-lg" value="@lang('forms.payment_next')" id="submitBtn" onclick="restrict()">

    	</form>
    </div>
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