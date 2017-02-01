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
				<form method="post" action="/a/advert/{{ $advert->id }}/{{ $advert->job_title }}/change/owner">
					{!! csrf_field() !!}
					<div class="form-group">
						<input type="number" name="company_id" class="form-control" id="business_id" value="{{ old('business_id') }}"placeholder="Add the company ID here" />
					</div>

					<div class="form-group">
						@if ($errors->has('company_id'))
		                    <span class="alert alert-danger">
		                        <strong>{{ $errors->first('company_id') }}</strong>
		                    </span>
	                	@endif
                	</div>
                	
                	<div class="form-group">
                		<button type="button" class="form-control" data-toggle="modal" data-target="#confirm">Change Owner</button>
                	</div>

                	<!-- Modal -->
					<div id="confirm" class="modal fade" role="dialog">
					  <div class="modal-dialog">

					    <!-- Modal content-->
					    <div class="modal-content">
					      <div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal">&times;</button>
					        <h4 class="modal-title">Are you sure? (This action cannot be undone)</h4>
					      </div>

					      <div class="modal-body">
					      	<center>
						      	<button type="button" class="btn btn-default" data-dismiss="modal">no</button>
						        <input type="submit" name="submitBtn" class="btn btn-default" id="submitBtn" value="yes" />
					        </center>
					      </div>

					      <div class="modal-footer">
					        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					      </div>
					    </div>

					  </div>
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