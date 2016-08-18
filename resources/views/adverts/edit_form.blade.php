@inject('countries', 'App\Http\Utilities\Country')

{{ csrf_field() }}

<div class="form-group">
	<label for="job_title">Job title:</label>
	<input type="text" name="job_title" id="job_title" class="form-control" value="{{ $advert->job_title }}" required>
</div>

<div class="form-group">
	<label for="salary">Salary:</label>
	<div class="input-group">
		<span class="input-group-addon">RM</span>
		<input type="text" name="salary" id="salary" class="form-control" value="{{ $advert->salary }}" required>
		<span class="input-group-addon">hourly</span>
</div>

<div class="form-group">
	<label for="rate">Rate:</label>
	<select name="rate" id="rate" class="form-control" required>
			<option value="{{ $advert->rate }}">{{ $advert->rate}}</option>
			<option value="hour">Per Hour</option>
			<option value="month">Per Month</option>
	</select>
</div>

<div class="form-group">
	<label for="oku_friendly">Is this job also suitable for OKU citizen?:</label>
	<select name="oku_friendly" id="oku_friendly" class="form-control" required>
			<option value="{{ $advert->oku_friendly }}">{{ $advert->oku_friendly }}</option>
			<option value="yes">Yes</option>
			<option value="no">No</option>
	</select>
</div>

</div>

<div class="form-group">
	<label for="description">Job Description:</label>
	<textarea type="text" name="description" id="description" class="form-control" rows="10" required>{{ $advert->description }}</textarea>
</div>

<div class="form-group">
	<label for="location">Location of employment: (example Bangsar Shopping Center, KLCC Convention Center)</label>
	<input type="text" name="location" id="location" class="form-control" value="{{ $advert->location }}" required>
</div> 

<hr>

<div class="form-group">
	<label for="street">Street:</label>
	<input type="text" name="street" id="street" class="form-control" value="{{ $advert->street }}" required>
</div> 

<div class="form-group">
	<label for="city">City:</label>
	<input type="text" name="city" id="city" class="form-control" value="{{ $advert->city }}" required>
</div> 

<div class="form-group">
	<label for="zip">Zip:</label>
	<input type="text" name="zip" id="zip" class="form-control" value="{{ $advert->zip }}" required>
</div> 

<div class="form-group">
	<label for="state">State:</label>
	<input type="text" name="state" id="state" class="form-control" value="{{ $advert->state }}" required>
</div>	

<div class="form-group">
	<label for="country">Country:</label>
	<select name="country" id="country" class="form-control" required>
			<option value="{{ $advert->country }}">{{ $advert->country }}</option>
		@foreach ($countries::all() as $code => $name)
			<option value="{{ $code }}">{{ $name }}</option>
		@endforeach
	</select>
</div>

<div class="form-group">
	<label for="skill">Type of skill required:</label>
	<input type="text" name="skill" id="skill" class="form-control" value="{{ $advert->skill }}" required>
</div>

<div class="form-group">
	<label for="category">Job Category:</label>
	<input type="text" name="category" id="category" class="form-control" value="{{ $advert->category }}" required>
</div>	

<div class="form-group">
	<button type="submit" class="btn btn-primary">Update Advertisement</button>
	<a href="/adverts" class="btn btn-primary">Cancel</a>	
</div> 