@extends('layouts.app')

@section('content')

<div class="row">

<form action="/subscribe/{{ $id }}" method="post">

<input type="radio" name="trial" id="trial0" value="trial"> 7 days Free Trial Plan</input>

<input type="radio" name="trial" id="trial1" value="1_month_plan"> 30 days Plan</input>

<div class="form-group">
	<button type="submit" class="btn btn-primary">Next : Make Payment</button>
</div>

</form>


</div>
@stop