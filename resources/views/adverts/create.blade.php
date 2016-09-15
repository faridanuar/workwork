@extends('layouts.app')

@section('content')

<div class="flash">
	@include('messages.flash')
</div>

<!-- <body onbeforeunload="return popUp()"> -->

<div class="panel-ww-advert panel panel-default center-block">
    <div class="panel-heading panel-heading-ww">New Part-time Ad</div>
    <div class="panel-body">
	    <form method="post" action="/" enctype="multipart/form-data">
			@if (count($errors) > 0)
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
			@endif

			@include('adverts.form')
		</form>
    </div>
</div>

@stop