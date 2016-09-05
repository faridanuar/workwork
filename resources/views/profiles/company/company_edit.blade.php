@extends('layouts.app')

@section('content')


	<h1>Edit Your Company Profile</h1>

	<hr>

	<div class="row">
		<form method="post" action="/company/update" enctype="multipart/form-data" class="col-md-6">
			@if (count($errors) > 0)
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
			@endif
			
			@include('profiles.company.company_edit_form')

		</form>
	</div>
	
@stop