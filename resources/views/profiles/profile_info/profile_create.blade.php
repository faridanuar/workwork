@extends('layouts.app')

@section('content')

	<div class="flash">
		@include('messages.flash')
	</div>

	<h1>Create Your Profile</h1>

	<hr>

		<form method="post" action="/profile/store" enctype="multipart/form-data" class="col-md-6">
			@if (count($errors) > 0)
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
			@endif
			
			@include('profiles.profile_info.profile_form_create')
@stop