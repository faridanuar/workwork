@inject('countries', 'App\Http\Utilities\Country')

{{ csrf_field() }}

<div class="form-group">
	<label for="introduction">Introduce yourself:</label>
	<textarea type="text" name="introduction" id="introduction" class="form-control" rows="10" required>{{ old('introduction') }}</textarea>
</div>

<hr>

<div class="form-group">
	<div>Applicant: {{ $user->name }}</div>
	<div>Age: {{ $jobSeeker->age }}</div>
	<div>Location: {{ $jobSeeker->location }}</div>
</div>

<div class="form-group">
	<div>Street: {{ $jobSeeker->street }}</div>
	<div>City: {{ $jobSeeker->city }}</div>
	<div>Zip: {{ $jobSeeker->zip }}</div>
	<div>State: {{ $jobSeeker->state }}</div>
	<div>Country: {{ $jobSeeker->country }}</div>
</div>

<hr>

<div class="form-group">
	<button type="submit" id="submitBtn" class="btn btn-primary" onclick="restrict()">Submit</button>
</div> 

@include('js_plugins.submit_restrict')