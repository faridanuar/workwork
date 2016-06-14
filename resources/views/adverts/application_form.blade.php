

{{ csrf_field() }}

<div class="form-group">
	<label for="biodata">Introduce yourself:</label>
	<textarea type="text" name="biodata" id="biodata" class="form-control" rows="10" required>{{ old('biodata') }}</textarea>
</div>


<div class="form-group">
	<button type="submit" class="btn btn-primary">Create Advertisement</button>
</div> 