@extends('layouts.app')

@section('content')
<h1>Apply a Job?</h1>

<hr>

<div class="row">
	<form method="post" action="/adverts/{id}/{job_title}/apply/job_seeker" enctype="multipart/form-data" class="col-md-6">
		@if (count($errors) > 0)
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
		@endif
		
		@include('adverts.application_form')

	</form>
</div>
@stop