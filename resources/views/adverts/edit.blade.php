@extends('layouts.app')
@section('content')
<div class="ftu-panel panel-ww-600 panel panel-default center-block">
    <div class="panel-heading panel-heading-ww">Edit {{ $advert->job_title }}</div>
    	<div class="panel-body">
			<form method="post" action="/adverts/{{ $advert->id }}/{{ $advert->job_title }}/edit/update" enctype="multipart/form-data">

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
	</div>
</div>
@stop
@section('js_plugins')
	@include('js_plugins.algolia_places')
	@include('js_plugins.tagging')
	@include('js_plugins.datetime_picker')
@stop