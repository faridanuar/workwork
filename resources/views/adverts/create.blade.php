@extends('layout')

@section('content')
<h1>Need a Part-timer?</h1>

<hr>

<div class="row">
	<form method="post" action="/adverts" enctype="multipart/form-data" class="col-md-6">
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
@stop