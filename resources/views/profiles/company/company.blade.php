@extends('layouts.app')

@section('content')


	<div class="flash">
		@include('messages.flash')
	</div>

	<h2>{{ $company->business_name }}</h2>
	<p>
		@if ($average)
		{{ $average }} out of 5 STAR
		@else
			No ratings yet
		@endif
	</p>
	<hr>

	<img id="business_logo" src="{{ $photo }}" height="100" width="100"/> 
	@can('edit_company')
		@if($authorize === true)
			<a href="/logo" class="btn btn-default btn-sm">Add Logo</a>
		@endif
	@endcan

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

	<a href="/company/{{ $company->id }}/{{ $company->business_name }}/review">Reviews ({{ $ratings }})</a>

	@can('edit_company')
		@if($authorize === true)
			<div>
				<a href="/company/edit" class="btn btn-primary">EDIT</a>
			</div>
		@endif
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
@stop