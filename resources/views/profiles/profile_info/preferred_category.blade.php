@extends('layouts.app')

@section('content')

@include('messages.ftu_level')

@inject('categories', 'App\Http\Utilities\Category')

<h2>Select Your Preferred Job Categories</h2>
<form method="post" action="/selected-category">
    {!! csrf_field() !!}
	<div class="btn-group" data-toggle="buttons" role="group" aria-label="...">
     @foreach ($categories::all() as $code => $name)
			<label class="btn btn-default">
        		<input 
        		type="checkbox" 
        		name="job_category[]" 
        		id= "job_category" 
        		autocomplete="off" 
        		value="{{ $code }}" 
        		/> {{ $name }}
     		</label>
		@endforeach
    </div>

    <input type="submit" class="btn btn-primary" />
</form>
@stop