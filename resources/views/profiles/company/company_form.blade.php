{{ csrf_field() }}

<div class="form-group">
	<label for="business_name">Business Name:</label>
	<input type="text" name="business_name" id="business_name" class="form-control" value="{{ $employer->business_name }}" required>
</div>

<div class="form-group">
	<label for="business_category">Business Category:</label>
	<input type="text" name="business_category" id="business_category" class="form-control" value="{{ $employer->business_category }}" required>
</div>

<div class="form-group">
	<label for="business_contact">Business Contact:</label>
	<input type="text" name="business_contact" id="business_contact" class="form-control" value="{{ $employer->business_contact }}" required>
</div>

<div class="form-group">
	<label for="business_website">Business Website (optional):</label>
	<input type="text" name="business_website" id="business_website" class="form-control" value="{{ $employer->business_website }}" required>
</div>	


<hr>

<h3>Company / Business Location</h3>

<div class="form-group">
	<label for="location">Location:</label>
	<input type="text" name="location" id="location" class="form-control" value="{{ $employer->location }}" required>
</div>

<div class="form-group">
	<label for="street">Street:</label>
	<input type="text" name="street" id="street" class="form-control" value="{{ $employer->street }}" required>
</div> 

<div class="form-group">
	<label for="city">City:</label>
	<input type="text" name="city" id="city" class="form-control" value="{{ $employer->city }}" required>
</div> 

<div class="form-group">
	<label for="zip">Zip:</label>
	<input type="text" name="zip" id="zip" class="form-control" value="{{ $employer->zip }}" required>
</div> 

<div class="form-group">
	<label for="state">State:</label>
	<input type="text" name="state" id="state" class="form-control" value="{{ $employer->state }}" required>
</div>

<hr>

<div class="form-group">
	<label for="company_intro">Company / Employer Introduction:</label>
	<textarea type="text" name="company_intro" id="company_intro" class="form-control" rows="10" placeholder="Tell us about your business..." required>{{ $employer->company_intro }}</textarea>
</div>

<div class="form-group">
	<button type="submit" class="btn btn-primary">Save Profile</button>
</div> 