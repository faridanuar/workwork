@inject('categories', 'App\Http\Utilities\Category')

{{ csrf_field() }}

<div class="form-group">
	<label for="business_name">@lang('forms.business_name')</label>
	<input type="text" name="business_name" id="business_name" class="form-control" value="{{ old('business_name') }}" required>
</div>

<div class="form-group">
	<label for="business_category">@lang('forms.business_category')</label>
	<!-- <input type="text" name="business_category" id="business_category" class="form-control" value="{{ old('business_category') }}" required> -->
	<select name="business_category" id="business_category" class="form-control">
			<option value=""  disabled selected>@lang('forms.business_category_selection')</option>
			@foreach ($categories::all() as $code => $name)
				<option value="{{ $code }}">{{ $name }}</option>
			@endforeach
	</select>
</div>

<div class="form-group">
	<label for="business_contact">@lang('forms.business_phone')</label>
	<input type="tel" name="business_contact" id="business_contact" class="form-control" value="{{ old('business_contact') }}" required>
</div>

<div class="form-group">
	<label for="company_intro">@lang('forms.business_introduction')</label>
	<span id="helpBlock_companyIntro" class="help-block">@lang('forms.business_introduction_helper')</span>
	<textarea type="text" name="company_intro" id="company_intro" class="form-control" rows="10" aria-describedby="helpBlock_companyIntro" maxlength="500">{{ old('company_intro') }}</textarea>
</div>

<div class="form-group">
	<button type="submit" class="btn btn-primary" 
	id="submitBtn" onclick="restrict()">@lang('forms.business_save')</button>
</div>

@include('js_plugins.submit_restrict')