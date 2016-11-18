@extends('layouts.app')

@section('content')
<div class="ftu-panel panel-ww-600 panel panel-default center-block">
    <div class="panel-heading panel-heading-ww">Edit Your Job Seeker Profile:</div>
		<div class="panel-body">
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
	</div>
</div>
@stop