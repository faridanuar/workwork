@inject('countries', 'App\Http\Utilities\Country')

{{ csrf_field() }}

<div class="form-group">
	<label for="job-title">Job title:</label>
	<input type="text" name="job-title" id="job-title" class="form-control" value="{{ old('job-title') }}" required>
</div>

<div class="form-group">
	<label for="salary">Salary:</label>
	<div class="input-group">
		<span class="input-group-addon">RM</span>
		<input type="text" name="salary" id="salary" class="form-control" value="{{ old('salary') }}" required>
		<span class="input-group-addon">hourly</span>
	</div>

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
	<label for="business-name">Business name:</label>
	<input type="text" name="business-name" id="business-name" class="form-control" value="{{ old('business-name') }}" required>
</div>

<div class="form-group">
	<label for="business-logo">Business logo: (optional)</label>
	<input type="file" name="business-logo" id="business-logo" class="form-control" value="{{ old('business-logo') }}">
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
		@foreach ($countries::all() as $code => $name)
			<option value="{{ $code }}">{{ $name }}</option>
		@endforeach
	</select>
</div> 

<div class="form-group">
	<button type="submit" class="btn btn-primary">Create Advertisement</button>
</div> 