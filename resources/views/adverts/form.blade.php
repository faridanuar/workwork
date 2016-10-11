@inject('countries', 'App\Http\Utilities\Country')
@inject('categories', 'App\Http\Utilities\Category')

{{ csrf_field() }}

<div class="form-group">
	<label for="job_title">@lang('forms.ad_job_title_label')</label>
	<input type="text" name="job_title" id="job_title" class="form-control"  value="{{ old('job_title') }}" maxlength="50" required>
</div>

<div class="form-group">
	<label for="salary">@lang('forms.ad_salary_label')</label>
	<p class="help-block">@lang('forms.ad_salary_help')</p>
	<div class="input-group ww-salary-input">
		<span class="input-group-addon ww-salary-input--currency">RM</span>
		<input type="number" name="salary" id="salary" class="form-control ww-salary-input--amount" value="{{ old('salary') }}" placeholder="0" inputmode="numeric">
		<span class="input-group-addon ww-salary-input--rate">
			<label class="radio-inline"><input type="radio" aria-label="..." name="rate" id="rate0" value="hour" checked> @lang('forms.ad_salary_hour')</label>
			<label class="radio-inline"><input type="radio" aria-label="..." name="rate" id="rate1" value="day"> @lang('forms.ad_salary_day')</label>
			<label class="radio-inline"><input type="radio" aria-label="..." name="rate" id="rate2" value="month"> @lang('forms.ad_salary_month')</label>
		</span>
	</div>
</div>

<div class="form-group">

</div>

<div class="form-group">
	<label for="location">@lang('forms.ad_location_label')</label>
	<p class="help-block">@lang('forms.ad_location_help')</p>

	<input
		type="search"
		class="form-control"
		id="address-input"
	   	name="location"
	    value="{{ old('location') }}"
	    placeholder="@lang('forms.ad_location_placeholder')"
	 />

	<p class="ftu-or">@lang('forms.or') <a href="#collapseAddress" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="collapseAddress"><span class="btn-garis">@lang('forms.ad_location_manual_label')</span></a></p>
	<div class="collapse" id="collapseAddress">
		<div class="well">
			<div class="form-group">
				<label for="street">@lang('forms.ad_location_street_label')</label>
				<input type="text" name="street" id="street" class="form-control" value="{{ old('street') }}">
			</div>

			<div class="form-group">
				<label for="city">@lang('forms.ad_location_city_label')</label>
				<input type="text" name="city" id="city" class="form-control" value="{{ old('city') }}">
			</div>

			<div class="form-group">
				<label for="zip">@lang('forms.ad_location_zip_label')</label>
				<input type="text" name="zip" id="zip" class="form-control" value="{{ old('zip') }}">
			</div>

			<div class="form-group">
				<label for="state">@lang('forms.ad_location_state_label')</label>
				<input type="text" name="state" id="state" class="form-control" value="{{ old('state') }}">
			</div>

			<div class="form-group">
				<label for="country">@lang('forms.ad_location_country_label')</label>
				<select name="country" id="country" class="form-control">
						<option value="Malaysia" selected>Malaysia</option>
					@foreach ($countries::all() as $code => $name)
						<option value="{{ $code }}">{{ $name }}</option>
					@endforeach
				</select>
			</div>
		</div>
	</div>
</div>

{{--<!--
<div class="form-group">
	<label for="schedule">Schedule</label>
	<p class="help-block">When do you need part-timers?</p>
	<textarea type="text" name="schedule" id="schedule" class="form-control" rows="10">{{ old('schedule') }}</textarea>
</div>
-->--}}

<div class="form-group">
	<label for="description">@lang('forms.ad_job_description_label')</label>
	<textarea type="text" name="description" id="description" class="form-control" rows="10">{{ old('description') }}</textarea>
</div>

<div class="form-group">
	{{--<!-- <label for="skill">@lang('forms.ad_job_skills_label')</label>
	<p class="help-block">e.g.: Teamwork, Multitasking</p>
	<input type="text" name="skill" id="skill" class="form-control" value="{{ old('skill') }}" maxlength="50" placeholder="e.g. Teamwork, Multitasking"> --> --}}
	<div class="form-group">
		<label for="skill">@lang('forms.ad_job_skills_label')</label>
		<p class="help-block">@lang('forms.ad_job_skills_help')</p>
		<input type="text" name="skills" id="skills" value="{{ old('skills') }}" data-role="tagsinput" />
	</div>
</div>

<div class="form-group">
	<label for="category">@lang('forms.ad_category_label')</label>
	<select name="category" id="category" class="form-control">
			<option value="{{ old('description') }}"  disabled selected>{{ old('description') }}</option>
		@foreach ($categories::all() as $code => $name)
			<option value="{{ $code }}">{{ $name }}</option>
		@endforeach
	</select>
</div>

<div class="form-group">
	<label for="oku_friendly">@lang('forms.ad_disabled_label')</label>
	<p class="help-block">@lang('forms.ad_disabled_help')</p>
	<label class="checkbox-inline">
		<input type="checkbox" id="oku_friendly" name="oku_friendly" value="yes"> @lang('forms.ad_disabled_yes')
	</label>
</div>
<hr>
<div class="form-group">
	<button type="submit" class="btn btn-primary btn-lg btn-block btn-ww-lg" id="submitBtn" onclick="restrict()">@lang('forms.ad_next') <span class="btn-arrow">&#8594;</span></button>
	<button type="submit" class="btn btn-link btn-block ftu-or" name="saveLater" value=true id="saveLater">@lang('forms.or') <span class="btn-garis">@lang('forms.ad_save')</span></button>
</div>

@include('js_plugins.algolia_places')
@include('js_plugins.tagging')
@include('js_plugins.submit_restrict')
