@extends('layouts.app')

@section('content')
<h1>Edit {{ $job->job_title }}</h1>

<hr>

<div class="row">
	<form method="post" action="/adverts/{{ $job->id }}/{{ $job->job_title }}/edit/update" enctype="multipart/form-data" class="col-md-6">

		@if (count($errors) > 0)
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
		@endif
		
		@include('adverts.edit_form')

	</form>
</div>
@stop