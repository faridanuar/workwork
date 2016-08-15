@extends('layouts.app')

@section('content')


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
			
			@include('profiles.company.company_form_create')

		</form>
	</div>
	
@stop