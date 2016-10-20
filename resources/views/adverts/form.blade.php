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
			<label class="radio-inline">
				<input type="radio" aria-label="..." name="rate" id="rate0" value="hour" 
					@if(old('rate') === 'hour')
						checked
					@elseif(old('rate') === null)
						checked 
					@endif
				/> @lang('forms.ad_salary_hour')
			</label>
			<label class="radio-inline">
				<input type="radio" aria-label="..." name="rate" id="rate1" value="day"
					@if(old('rate') === 'day')
						checked
					@endif
				/> @lang('forms.ad_salary_day')
			</label>
			<label class="radio-inline">
				<input type="radio" aria-label="..." name="rate" id="rate2" value="month" 
					@if(old('rate') === 'month')
						checked
					@endif
				/> @lang('forms.ad_salary_month')
			</label>
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



<div class="form-group">
	<div>Schedule</div>
</div>

<div class="form-group">
	<label class="radio-inline"><input type="radio" aria-label="..." name="scheduleType" id="scheduleType0" value="none"
		@if(old('scheduleType') === 'none')
			checked
		@elseif(old('scheduleType') === null)
			checked 
		@endif
	 /> No Schedule</label>
</div>

<div class="form-group">
	<label class="radio-inline">
		<input type="radio" aria-label="..." name="scheduleType" id="scheduleType1" value="specific" 
			@if(old('scheduleType') === 'specific')
				checked
			@endif
		/> Specific
	</label>
	<div>
		<label for="specific">Start Date</label>
			<input type='text' class="form-control" name="startDate" id='datetimepicker1' value="{{ old('startDate') }}" />
		<label for="specific">End Date</label>
			<input type='text' class="form-control" name="endDate" id='datetimepicker2' value="{{ old('endDate') }}" />
		<label for="specific">Start At</label>
			<input type='text' class="form-control" name="startTime" id='datetimepicker3' value="{{ old('startTime') }}" />
		<label for="specific">Ends At</label>
			<input type='text' class="form-control" name="endTime" id='datetimepicker4' value="{{ old('endTime') }}" />
	</div>
</div>

<div class="form-group">
	<label class="radio-inline">
		<input type="radio" aria-label="..." name="scheduleType" id="scheduleType2" value="daily"
			@if(old('scheduleType') === 'daily')
				checked
			@endif
		/> Daily
	</label>
	@for($i = 1; $i <= 7; $i++)
		<div>
			<input type="checkbox" name="day[{{ $i }}]" id="day{{ $i }}" value="{{ $dayName->find($i)->day }}" 
				@if(old('day.'.$i) === $dayName->find($i)->day)
					checked
				@endif
			/> {{ $dayName->find($i)->day }}
				<div for="specific">Start At</div>
					<input type='text' class="form-control" name="startDayTime[{{ $i }}]" id="datetimepicker{{ $i+10 }}" 
					value="{{ old('startDayTime.'.$i) }}" />
				<div for="specific">Ends At</div>
					<input type='text' class="form-control" name="endDayTime[{{ $i }}]" id="datetimepicker{{ $i+20}}" 
					value="{{ old('endDayTime.'.$i) }}" />
		</div>
	@endfor
</div>

<div class="form-group">
	<label for="description">@lang('forms.ad_job_description_label')</label>
	<textarea type="text" name="description" id="description" class="form-control" rows="10">{{ old('description') }}</textarea>
</div>

<div class="form-group">
	<div class="form-group">
		<label for="skill">@lang('forms.ad_job_skills_label')</label>
		<p class="help-block">@lang('forms.ad_job_skills_help')</p>
		<input type="text" name="skills" id="skills" value="{{ old('skills') }}" data-role="tagsinput" />
	</div>
</div>

<div class="form-group">
	<label for="category">@lang('forms.ad_category_label')</label>
	<select name="category" id="category" class="form-control">
			<option value="{{ old('category') }}" selected>{{ old('category') }}</option>
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
@include('js_plugins.datetime_picker')
