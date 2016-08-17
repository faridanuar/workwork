@extends('layouts.app')

@section('content')


	<div class="flash">
		@include('messages.flash')
	</div>


	<h1>Upload your Avatar Photo</h1>

	<hr>
	<h4>(Current Photo)</h4>
	<img id="photo"  src="{{ $user->avatar }}" onerror="imgError(this);" height="150" width="160" alt="your image" />

	<hr>


	<div class="upload-box">
		<form id="addPhotosForm" method="post" action="/avatar/upload" enctype="multipart/form-data" class="dropzone">
		{{ csrf_field() }}

		</form>
	</div>

	<p>

	<div class="form-group">
		<a href="/dashboard" class="btn btn-primary">Done</a>
	</div>

	@include('java_plugins.dropzone')

@stop