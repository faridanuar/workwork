@extends('layouts.app')

@section('content')
<div class="flash">
	@include('messages.flash')
</div>

<div>
	@include('messages.ftu_level')
</div>

<div class="ftu-panel panel-ww-600 panel panel-default center-block">
    <div class="panel-heading panel-heading-ww">Create Your Profile</div>
		<div class="panel-body">
			<form method="post" action="/profile/store" enctype="multipart/form-data" id="myForm">
				@if (count($errors) > 0)
				<div class="alert alert-danger">
					<ul>
						@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
				@endif
				
				@include('profiles.job_seeker.profile_form_create')
			</form>
		</div>
	</div>
</div>
@stop

@section('js_plugins')
	@include('js_plugins.submit_restrict')
@stop