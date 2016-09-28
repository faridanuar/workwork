@extends('layouts.app')

@section('content')

<div class="flash">
	@include('messages.flash')
</div>

@if($user->ftu_level != 'completed')
	<ul><li>Current</li><li>Not Done</li><li>Not Done</li><li>Not Done</li></ul>
@endif

<h1>Create Your Company Profile</h1>

<hr>

<div class="row">
	<form method="post" action="/company/store" enctype="multipart/form-data" class="col-md-6">
		@if (count($errors) > 0)
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
		@endif
		
		@include('profiles.company.company_create_form')

	</form>
</div>

@stop