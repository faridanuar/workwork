@extends('layouts.app')

@section('js_stylesheets')
	@include('js_plugins.js_stylesheets.dropzone_css')
@stop

@section('content')
<div class="flash">
	@include('messages.flash')
</div>

<div class="panel-ww-600 panel-default center-block">
	<div class="panel panel-default">
		<div class="panel-body">

			<div>
				Upload your Avatar Photo
			</div>

			<hr>

			<img id="photo"  src="{{ $photo }}" height="180" width="200" alt="your image" />

			<hr>

			<div class="upload-box">
				<form id="addPhotosForm" method="post" action="/avatar/upload" enctype="multipart/form-data" class="dropzone">
				{!! csrf_field() !!}

				</form>
			</div>

			<p></p>

			<div class="form-group">
				<form method="post" action="/avatar/{{ $user->id }}">
					{!! csrf_field() !!}

					<input type="hidden" name="_method" value="DELETE">

					@if($fileExist === true)
						<button type="submit" class="btn btn-primary">Remove</button>
					@endif
						<a href="/dashboard" class="btn btn-primary">Done</a>
				</form>
			</div>
		</div>
	</div>
</div>
@stop

@section('js_plugins')
	@include('js_plugins.dropzone')
@stop