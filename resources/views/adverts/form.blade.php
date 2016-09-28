@inject('countries', 'App\Http\Utilities\Country')
@inject('categories', 'App\Http\Utilities\Category')

{{ csrf_field() }}

<div class="form-group">
	<label for="job_title">Part-time job title</label>
	<input type="text" name="job_title" id="job_title" class="form-control"  value="{{ old('job_title') }}" maxlength="50" required>
</div>

<div class="form-group">
	<label for="salary">Salary</label>
	<p class="help-block">You can choose hourly, daily or monthly</p>
	<div class="input-group ww-salary-input">
		<span class="input-group-addon ww-salary-input--currency">RM</span>
		<input type="text" name="salary" id="salary" class="form-control ww-salary-input--amount" value="{{ old('salary') }}" placeholder="0" inputmode="numeric">
		<span class="input-group-addon ww-salary-input--rate">
			<label class="radio-inline"><input type="radio" aria-label="..." name="rate" id="rate0" value="hour" checked> Hourly</label>
			<label class="radio-inline"><input type="radio" aria-label="..." name="rate" id="rate1" value="day"> Daily</label>
			<label class="radio-inline"><input type="radio" aria-label="..." name="rate" id="rate2" value="month"> Monthly</label>
		</span>
	</div>
</div>

<div class="form-group">

</div>

<div class="form-group">
	<label for="location">Location</label>
	<p class="help-block">Where will the job be located?</p>

	<input
		type="search"
		class="form-control"
		id="address-input"
	   	name="location"
	    value="{{ old('location') }}"
	    placeholder="Work Location?"
	 />

	<p>or <a href="#collapseAddress" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="collapseAddress"> Enter address</a></p>
	<div class="collapse" id="collapseAddress">
		<div class="well">
			<div class="form-group">
				<label for="street">Street:</label>
				<input type="text" name="street" id="street" class="form-control" value="{{ old('street') }}">
			</div>

			<div class="form-group">
				<label for="city">City:</label>
				<input type="text" name="city" id="city" class="form-control" value="{{ old('city') }}">
			</div>

			<div class="form-group">
				<label for="zip">Zip:</label>
				<input type="text" name="zip" id="zip" class="form-control" value="{{ old('zip') }}">
			</div>

			<div class="form-group">
				<label for="state">State:</label>
				<input type="text" name="state" id="state" class="form-control" value="{{ old('state') }}">
			</div>

			<div class="form-group">
				<label for="country">Country:</label>
				<select name="country" id="country" class="form-control">
						<option value=""  disabled selected>Select a country</option>
					@foreach ($countries::all() as $code => $name)
						<option value="{{ $code }}">{{ $name }}</option>
					@endforeach
				</select>
			</div>
		</div>
	</div>
</div>

<!--
<div class="form-group">
	<label for="schedule">Schedule</label>
	<p class="help-block">When do you need part-timers?</p>
	<textarea type="text" name="schedule" id="schedule" class="form-control" rows="10">{{ old('schedule') }}</textarea>
</div>
-->

<div class="form-group">
	<label for="description">Job Description</label>
	<textarea type="text" name="description" id="description" class="form-control" rows="10">{{ old('description') }}</textarea>
</div>

<div class="form-group">
	<label for="skill">Skills Needed</label>
	<!-- <p class="help-block">e.g.: Teamwork, Multitasking</p>
	<input type="text" name="skill" id="skill" class="form-control" value="{{ old('skill') }}" maxlength="50" placeholder="e.g. Teamwork, Multitasking"> -->
	<div class="form-group">
		<label for="skill">(Example: Teamwork, Multitasking)</label>
		<input type="text" name="skills" id="skills" value="" data-role="tagsinput" />
	</div>
</div>

<div class="form-group">
	<label for="category">Category</label>
	<select name="category" id="category" class="form-control">
			<option value=""  disabled selected>Select a category</option>
		@foreach ($categories::all() as $code => $name)
			<option value="{{ $code }}">{{ $name }}</option>
		@endforeach
	</select>
</div>

<div class="form-group">
	<label for="oku_friendly">Disabled Friendly</label>
	<p class="help-block">Is this job suitable for people with disabilities?</p>
	<label class="checkbox-inline">
		<input type="checkbox" id="oku_friendly" name="oku_friendly" value="yes"> Yes
	</label>
</div>
<hr>
<div class="form-group">
	<button type="submit" class="btn btn-default" id="saveLater" name="saveLater" value=true>Save For Later</button>
	<button type="submit" class="btn btn-primary">Next : Choose Plan</button>
</div>

@include('js_plugins.algolia_places')
@include('js_plugins.tagging')