{{ csrf_field() }}

<div class="form-group">
	<label for="business_logo">Business logo: (optional)</label>
	<input type="file" name="business_logo" id="business_logo" class="form-control" value="{{ old('business_logo') }}">
</div> 	


<div class="form-group">
	<a href="/adverts" class="btn btn-primary">Cancel</a>
	<button type="submit" class="btn btn-primary">Save Photo</button>
</div> 