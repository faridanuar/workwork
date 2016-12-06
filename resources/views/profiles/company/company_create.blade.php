@extends('layouts.app')
@section('content')
<div class="flash">
	@include('messages.flash')
</div>

@include('messages.ftu_level')

<h1 class="ftu-intro">@lang('ftu.business_profile')</h1>

<div class="ftu-panel panel-ww-600 panel panel-default center-block">
	<div class="ftu-arrow"></div>
    <div class="panel-heading panel-heading-ww">@lang('forms.business_profile')</div>
    <div class="panel-body">
		<form method="post" action="/company/store" enctype="multipart/form-data" id="myForm">
			@if (count($errors) > 0)
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
			@endif

			@include('profiles.company.company_create_form')

		</form>
	</div>
</div>
@stop
@section('js_plugins')
	@include('js_plugins.submit_restrict')
@stop