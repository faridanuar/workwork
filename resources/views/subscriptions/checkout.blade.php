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

        <div class="form-group">
    		  <input type="submit" id="pay" class="btn btn-primary btn-lg btn-block btn-ww-lg" value="@lang('forms.payment_next')">
        </div>
    	</form>
    </div>
</div>
@stop

@section('js_plugins')
  <script>
  $(document).ready(function(){

    var clientToken = "{{ $token }}";

    braintree.setup(clientToken, "dropin", {
      container: "payment-form"
    });
      
    $( "#checkout" ).submit(function( event ) {
      $("#pay")
        .val("Please Wait...")
        .attr('disabled', true);
      if( $( ":input" ).val() != "" ) {
        return true;
      }else{
        $("#pay")
        .val("@lang('forms.payment_next')")
        .attr('disabled', false);

        event.preventDefault();
      }

      //setTimeout(function(){
        
        //}, 5000);
    });

  });
  </script>
@stop