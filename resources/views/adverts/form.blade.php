@inject('countries', 'App\Http\Utilities\Country')
@inject('categories', 'App\Http\Utilities\Category')

{{ csrf_field() }}

<div class="form-group">
	<label for="job_title">Job title:</label>
	<input type="text" name="job_title" id="job_title" class="form-control" value="{{ old('job_title') }}" required>
</div>

<div class="form-group">
	<label for="salary">Salary:</label>
	<div class="input-group">
		<span class="input-group-addon">RM</span>
		<input type="text" name="salary" id="salary" class="form-control" value="{{ old('salary') }}" required>
		<span class="input-group-addon"></span>
	</div>
</div>

<div class="form-group">
	<label for="rate">Rate:</label>
	<select name="rate" id="rate" class="form-control" required>
			<option value="" selected disabled>Select a salary rate</option>
			<option value="hour">Per Hour</option>
			<option value="month">Per Month</option>
	</select>
</div>

<div class="form-group">
	<label for="oku_friendly">Is this job also suitable for OKU citizen?:</label>
	<select name="oku_friendly" id="oku_friendly" class="form-control" required>
			<option value="" selected disabled>Choose an Answer</option>
			<option value="yes">Yes</option>
			<option value="no">No</option>
	</select>
</div>


<div class="form-group">
	<label for="description">Job Description:</label>
	<textarea type="text" name="description" id="description" class="form-control" rows="10" required>{{ old('description') }}</textarea>
</div>

<div class="form-group">
	<label for="location">Location of employment: (example Bangsar Shopping Center, KLCC Convention Center)</label>
	<input type="text" name="location" id="location" class="form-control" value="{{ old('location') }}" required>
</div> 

<hr>

<div class="form-group">
	<label for="business_name">Business name:</label>
	<input type="text" name="business_name" id="business_name" class="form-control" value="{{ old('business_name') }}" required>
</div>

<div class="form-group">
	<label for="business_logo">Business logo: (optional)</label>
	<input type="file" name="business_logo" id="business_logo" class="form-control" value="{{ old('business_logo') }}">
</div> 	

<div class="form-group">
	<label for="street">Street:</label>
	<input type="text" name="street" id="street" class="form-control" value="{{ old('street') }}" required>
</div> 

<div class="form-group">
	<label for="city">City:</label>
	<input type="text" name="city" id="city" class="form-control" value="{{ old('city') }}" required>
</div> 

<div class="form-group">
	<label for="zip">Zip:</label>
	<input type="text" name="zip" id="zip" class="form-control" value="{{ old('zip') }}" required>
</div> 

<div class="form-group">
	<label for="state">State:</label>
	<input type="text" name="state" id="state" class="form-control" value="{{ old('state') }}" required>
</div>

<div class="form-group">
	<label for="country">Country:</label>
	<select name="country" id="country" class="form-control" required>
			<option value="" selected disabled>Select a country</option>
		@foreach ($countries::all() as $code => $name)
			<option value="{{ $code }}">{{ $name }}</option>
		@endforeach
	</select>
</div>

<div class="form-group">
	<label for="skill">Type of skill required:</label>
	<input type="text" name="skill" id="skill" class="form-control" value="{{ old('skill') }}" required>
</div>

<div class="form-group">
	<label for="category">Job Category:</label>
	<select name="category" id="category" class="form-control" required>
			<option value="" selected disabled>Select a category</option>
		@foreach ($categories::all() as $code => $name)
			<option value="{{ $code }}">{{ $name }}</option>
		@endforeach
	</select>
</div>	

<div class="form-group">
	<button type="submit" class="btn btn-primary">Create Advertisement</button>
</div> 