@extends('layouts.app')

@section('content')

@if($user->ftu_level < 4)
	@include('messages.ftu_level')
@else
	@include('messages.advert_level')
@endif

<h4>Choose a plan</h4>

<form action="/checkout/{{ $id }}" method="post">

{!! csrf_field() !!}

<div class="form-group">
<input type="radio" name="plan" id="plan0" value="Trial"> 30 Freemium Plan (After Successful hiring, RM50 fee may apply)</input> || 

<input type="radio" name="plan" id="plan1" value="1_Month_Plan" checked="checked" required> 30 days Plan</input>
</div>

<div class="form-group">
	<button type="submit" class="btn btn-primary">Next : Make Payment</button>
</div>

</form>

@stop