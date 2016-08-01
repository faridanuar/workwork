{{ csrf_field() }}

<div class="form-group">
	<label for="business_name">Business Name:</label>
	<input type="text" name="business_name" id="business_name" class="form-control" value="{{ old('company_name') }}" required>
</div>

<div class="form-group">
	<label for="business_logo">Business logo: (optional)</label>
	<input type="file" name="business_logo" id="business_logo" class="form-control" value="{{ old('business_logo') }}">
</div> 	

<hr>

<h3>Company Location</h3>

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
	<label for="skill">Type of skill required:</label>
	<input type="text" name="skill" id="skill" class="form-control" value="{{ old('skill') }}" required>
</div>

<hr>

<div class="form-group">
	<label for="description">Company Introduction:</label>
	<textarea type="text" name="description" id="description" class="form-control" rows="10" placeholder="Tell us about your company..." required>{{ old('description') }}</textarea>
</div>

<div class="form-group">
	<button type="submit" class="btn btn-primary">Create Advertisement</button>
</div> 