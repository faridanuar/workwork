@inject('countries', 'App\Http\Utilities\Country')
@inject('categories', 'App\Http\Utilities\Category')

{{ csrf_field() }}

<div class="form-group">
	<label for="job_title">Job title:</label>
	<input type="text" name="job_title" id="job_title" class="form-control" value="{{ $advert->job_title }}" maxlength="50" required>
</div>

<div class="form-group">
	<label for="salary">Salary:</label>
	<div class="input-group ww-salary-input">
		<span class="input-group-addon ww-salary-input--currency">RM</span>
		<input type="number" name="salary" id="salary" class="form-control" value="{{ $advert->salary }}">
		<span class="input-group-addon ww-salary-input--rate">
			<label class="radio-inline">
				<input type="radio" aria-label="..." name="rate" id="rate0" value="hour" 
					@if($advert->rate === 'hour')
						checked
					@endif
				/> @lang('forms.ad_salary_hour')
			</label>
			<label class="radio-inline">
				<input type="radio" aria-label="..." name="rate" id="rate1" value="day"
					@if($advert->rate === 'day')
						checked
					@endif
				/> @lang('forms.ad_salary_day')
			</label>
			<label class="radio-inline">
				<input type="radio" aria-label="..." name="rate" id="rate2" value="month" 
					@if($advert->rate === 'month')
						checked
					@endif
				/> @lang('forms.ad_salary_month')
			</label>
		</span>
	</div>
</div>

<div class="form-group">
	<label for="oku_friendly">Is this job also suitable for OKU citizen?:</label>
	<select name="oku_friendly" id="oku_friendly" class="form-control">
			<option value="{{ $advert->oku_friendly }}">{{ $advert->oku_friendly }}</option>
			<option value="yes">Yes</option>
			<option value="no">No</option>
	</select>
</div>

<div class="form-group">
	<label for="description">Job Description:</label>
	<textarea type="text" name="description" id="description" class="form-control" rows="10">{{ $advert->description }}</textarea>
</div>

<div class="form-group">
	<label for="location">Location of employment: (example Bangsar Shopping Center, KLCC Convention Center)</label>
	<input 
		type="search"
		class="form-control"
		id="address-input"
	   	name="location"
	    value="{{ $advert->location }}"
	    placeholder="Work Location?" 
	 />
</div>

<p class="ftu-or">@lang('forms.or') 
	<a href="#collapseAddress" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="collapseAddress">
		<span class="btn-garis">@lang('forms.ad_location_manual_label')</span>
	</a>
</p>
	<div id="collapseAddress"
		@if($advert->street)
			class="collapse in"
		@else
			class="collapse"
		@endif
	>
		<div class="well">
			<div class="form-group">
				<label for="street">@lang('forms.ad_location_street_label')</label>
				<input type="text" name="street" id="street" class="form-control" value="{{ $advert->street }}">
			</div>

			<div class="form-group">
				<label for="city">@lang('forms.ad_location_city_label')</label>
				<input type="text" name="city" id="city" class="form-control" value="{{ $advert->city }}">
			</div>

			<div class="form-group">
				<label for="zip">@lang('forms.ad_location_zip_label')</label>
				<input type="text" name="zip" id="zip" class="form-control" value="{{ $advert->zip }}">
			</div>

			<div class="form-group">
				<label for="state">@lang('forms.ad_location_state_label')</label>
				<input type="text" name="state" id="state" class="form-control" value="{{ $advert->state }}">
			</div>

			<div class="form-group">
				<label for="country">Country:</label>
				<select name="country" id="country" class="form-control">
						<option value="{{ $advert->country }}">{{ $advert->country }}</option>
					@foreach ($countries::all() as $code => $name)
						<option value="{{ $code }}">{{ $name }}</option>
					@endforeach
				</select>
			</div>
		</div>
	</div>

<hr>

<div class="form-group">
	<div>Schedule</div>
	<label class="radio-inline">
		<input type="radio" aria-label="..." name="scheduleType" id="scheduleType0" value="none"
			@if($scheduleType === 'none' || $scheduleType === "") 
				checked 
			@endif
		/> No Schedule
	</label>
</div>

<div class="form-group">
	<label class="radio-inline">
		<input type="radio" aria-label="..." name="scheduleType" id="scheduleType1" value="specific" 
			@if($scheduleType === 'specific') 
				checked 
			@endif
		/> Specific
	</label>
	<div>
		<label for="specific">Start Date</label>
			<input type='text' class="form-control" name="startDate" id='datetimepicker1' value="{{ $startDate }}" />
		<label for="specific">End Date</label>
			<input type='text' class="form-control" name="endDate" id='datetimepicker2' value="{{ $endDate }}" />
		<label for="specific">Start Time</label>
			<input type='text' class="form-control" name="startTime" id='datetimepicker3' value="{{ $startTime }}" />
		<label for="specific">End Time</label>
			<input type='text' class="form-control" name="endTime" id='datetimepicker4' value="{{ $endTime }}" />
	</div>
</div>

<div class="form-group">
	<label class="radio-inline"><input type="radio" aria-label="..." name="scheduleType" id="scheduleType2" value="daily" 
		@if($scheduleType === 'daily') 
			checked 
		@endif
	/> Daily</label>
	@for($i = 1; $i <= 7; $i++)
		<div>
			<input type="checkbox" name="day[{{ $i }}]" id="day{{ $i }}" value="{{ $dayName->find($i)->day }}" 
			@if($days != null)
				@if($days->find($i)) 
					checked 
				@endif
			@endif 
			/> {{ $dayName->find($i)->day }}
				<div for="specific">Start At</div>
					<input type='text' class="form-control" name="startDayTime[{{ $i }}]" id="datetimepicker{{ $i+10 }}"
						@if($days != null)
							@if($days->find($i)) 
								value="{{ $days->find($i)->pivot->start_time }}"
							@endif
						@endif
					/>
				<div for="specific">Ends At</div>
					<input type='text' class="form-control" name="endDayTime[{{ $i }}]" id="datetimepicker{{ $i+20}}"
						@if($days != null)
							@if($days->find($i)) 
								value="{{ $days->find($i)->pivot->end_time }}"
							@endif 
						@endif
					/>
		</div>
	@endfor
</div>

<div class="form-group">
	<label for="specific">Date Start</label>
		<input type='text' class="form-control" name="dailyStartDate" id='datetimepicker5' value="{{ $dailyStart }}" />
	<label for="specific">Date Ends</label>
		<input type='text' class="form-control" name="dailyEndDate" id='datetimepicker6' value="{{ $dailyEnd }}" />
</div>

<hr>

<div class="form-group">
	<label for="skill">Type of skill required:</label>
	<div class="form-group">
		<label for="skill">(Example: Teamwork, Multitasking)</label>
		<input type="text" name="skills" id="skills" value="{{ $skills }}" data-role="tagsinput" />
	</div>
</div>

<div class="form-group">
	<label for="category">Category</label>
	<select name="category" id="category" class="form-control">
			<option value="{{ $advert->category }}" selected>{{ $advert->category }}</option>
		@foreach ($categories::all() as $code => $name)
			<option value="{{ $code }}">{{ $name }}</option>
		@endforeach
	</select>
</div>	

<div class="form-group">
	<a href="/dashboard" class="btn btn-primary">Cancel</a>
	<button type="submit" class="btn btn-primary" id="saveLater" name="saveLater" value=true>Save For Later</button>
	<button type="submit" class="btn btn-primary">Update And Publish Advertisement</button>
</div>

@include('js_plugins.algolia_places')
@include('js_plugins.tagging')
@include('js_plugins.datetime_picker')