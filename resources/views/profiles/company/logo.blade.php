@extends('layouts.app')

@section('content')


	<div class="flash">
		@include('messages.flash')
	</div>


	<h1>Upload your Business / Company Photo</h1>

	<hr>
	<h4>(Current Photo)</h4>
	<img id="photo" src="{{ $photo }}" height="150" width="160" alt="your image" />

	<hr>


	<div class="upload-box">
		<form id="addPhotosForm" method="post" action="/upload/logo" enctype="multipart/form-data" class="dropzone">
		{{ csrf_field() }}

		</form>
	</div>

	<p>

	<div class="form-group">
		<form method="post" action="/logo/{{ $employer->id }}">
		{!! csrf_field() !!}
		<input type="hidden" name="_method" value="DELETE">

		@if($fileExist === true)
			<button type="submit" class="btn btn-primary">Remove</button>
		@endif
			<a href="/company/{{ $employer->id }}/{{ $employer->business_name }}" class="btn btn-primary">Done</a>
		</form>
	</div>
		
	@include('java_plugins.dropzone')

@stop