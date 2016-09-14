@extends('layouts.app')

@section('content')

<h4>Choose a plan</h4>

<form action="/checkout/{{ $id }}" method="post">

{!! csrf_field() !!}

<div class="form-group">
<input type="radio" name="plan" id="plan0" value="Trial"> 7 days Free Trial Plan</input> || 

<input type="radio" name="plan" id="plan1" value="1_Month_Plan" checked="checked" required> 30 days Plan</input>
</div>

<div class="form-group">
	<button type="submit" class="btn btn-primary">Next : Make Payment</button>
</div>

</form>

@stop