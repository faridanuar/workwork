@extends('layouts.app')

@section('content')


	<div class="flash">
		@include('messages.flash')
	</div>

	<img id="business_logo" src="{{ $company->business_logo }}" height="200" width="200" onerror="imgError(this);"/>

	
	<h1>{{ $company->business_name }}</h1>

	<h2>{{ $average }} out of 5</h2>

	<h4>Ratings: +{{ $ratingSum }}</h4>

	<hr>

		{{ $company->company_intro }}

		<hr>

		<div>
		{{ $company->business_category }}
		</div>

		<div>
		{{ $company->business_contact }}
		</div>

		<div>
		<b>{{ $company->business_website }}</b>
		</div>

		<div>
		<h4>Company Address</h4>
		</div>

		<div>
		{{ $company->location }}
		</div>

		<div>
		{{ $company->street }}
		</div>

		<div>
		{{ $company->city }}
		</div>

		<div>
		{{ $company->zip }}
		</div>

		<div>
		{{ $company->state }}
		</div>

		<a href="/company/{{ $company->id }}/{{ $company->business_name }}/review">Review</a>

		@can('edit_company')
				<div>
				<a href="/edit-company" class="btn btn-primary">EDIT</a>
				<a href="/logo" class="btn btn-primary">EDIT PHOTO</a>
				</div>
		@endcan

		@can('rate_company')
			@if($rated === false)
				<h4>Rate:</h4>

				<form action="/company/{{ $company->id }}/{{ $company->business_name }}/rate" method="post" id="rateForm">
				{{ csrf_field() }}

				<div class="btn-group" data-toggle="buttons">
			        <label class="btn btn-primary">
				        <input type="radio" name="star" id="star0" autocomplete="off" value="1"> 1 Star
			        </label>
			        <label class="btn btn-primary">
				        <input type="radio" name="star" id="star1" autocomplete="off" value="2"> 2 Star
			        </label>
			        <label class="btn btn-primary">
				        <input type="radio" name="star" id="star2" autocomplete="off" value="3"> 3 Star
			        </label>
			        <label class="btn btn-primary">
				        <input type="radio" name="star" id="star3" autocomplete="off" value="4"> 4 Star
			        </label>
			        <label class="btn btn-primary">
				        <input type="radio" name="star" id="star4" autocomplete="off" value="5"> 5 Star
			        </label>
	            </div>
	            <p>
	            <div class="form-group-comment">
					<label for="comment">Comment:</label>
					<textarea type="text" name="comment" id="comment" class="form-control" rows="5" required>{{ old('comment') }}</textarea>
				</div>
				<button type="submit" class="btn btn-primary">Submit</button>

				</form>

				@if (count($errors) > 0)
					<div class="alert alert-danger">
						<ul>
							@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif

			@else

			@endif

		@endcan


	
	@include('javaPlugins.defaultPhoto')
@stop