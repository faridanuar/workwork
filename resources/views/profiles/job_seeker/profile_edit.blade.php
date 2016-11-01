@extends('layouts.app')

@section('content')


	<h1>Edit Your Company Profile</h1>

	<hr>

	<div class="row">
		<form method="post" action="/profile/edit/update" enctype="multipart/form-data" class="col-md-6">
			@if (count($errors) > 0)
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
			@endif
			
			@include('profiles.job_seeker.profile_form_edit')

		</form>
	</div>
	
@stop