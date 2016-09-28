@inject('categories', 'App\Http\Utilities\Category')

{{ csrf_field() }}

<div class="form-group">
	<label for="business_name">Business Name:</label>
	<input type="text" name="business_name" id="business_name" class="form-control" value="{{ old('business_name') }}" required>
</div>

<div class="form-group">
	<label for="business_category">Business Category:</label>
	<!-- <input type="text" name="business_category" id="business_category" class="form-control" value="{{ old('business_category') }}" required> -->
	<select name="business_category" id="business_category" class="form-control">
			<option value=""  disabled selected>Select a Business category</option>
		@foreach ($categories::all() as $code => $name)
			<option value="{{ $code }}">{{ $name }}</option>
		@endforeach
	</select>
</div>

<div class="form-group">
	<label for="business_contact">Business Contact:</label>
	<input type="text" name="business_contact" id="business_contact" class="form-control" value="{{ old('business_contact') }}" required>
</div>

<div class="form-group">
	<label for="company_intro">Company / Employer Introduction:</label>
	<textarea type="text" name="company_intro" id="company_intro" class="form-control" rows="10" placeholder="Tell us about your business..." required>{{ old('company_intro') }}</textarea>
</div>

<div class="form-group">
	<button type="submit" class="btn btn-primary">Save Profile</button>
</div> 