@extends('layouts.app')

@section('js_stylesheets')
	
@stop

@section('content')
<div class="flash">
	@include('messages.flash')
</div>

<div class="panel-ww-600 panel-default center-block">
	<div class="panel panel-default">
		<div class="panel-heading panel-heading-ww">Changing Advert Owner</div>
		<div class="panel-body">

			<div class="form-group">
				Advert: {{ $advert->job_title }} <br />
				Current Owner: {{ $advert->employer->business_name }}
			</div>

			<div class="form-group">
				<form method="post" action="/a/advert/{{ $advert->id }}/{{ $advert->job_title }}/cnange/owner">
					{!! csrf_field() !!}
					<div class="form-group">
						<input type="number" name="business_id" class="form-control" id="business_id" value="{{ old('business_id') }}"placeholder="Add the company ID here" />
					</div>

					<div class="form-group">
						@if ($errors->has('business_id'))
		                    <span class="alert alert-danger">
		                        <strong>{{ $errors->first('business_id') }}</strong>
		                    </span>
	                	@endif
                	</div>
                	
                	<div class="form-group">
						<input type="submit" name="submitBtn" class="form-control" id="submitBtn" value="Change Owner" />
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-body">

			<h4>Search Companies:</h4>
			<form method="get" action="">
				<input type="text" name="business_name" class="form-control" id="business_name" placeholder="e.g WorkWork" />
			</form>

		</div>
	</div>

	@forelse($employers as $employer)
		<div class="panel panel-default">
			<div class="panel-body">
				<b>Company: {{ $employer->business_name }}</b> <br />
				Category: {{ $employer->business_category }} <br />
				ID: {{ $employer->id }}
			</div>
		</div>
	@empty
		<div class="panel panel-default">
			<div class="panel-body">
				No results...
			</div>
		</div>
	@endforelse
</div>
@stop

@section('js_plugins')

@stop