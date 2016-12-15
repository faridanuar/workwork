@extends('layouts.app')

@section('content')
@include('messages.ftu_level')

@inject('categories', 'App\Http\Utilities\Category')

<div class="ftu-panel panel-ww-600 panel panel-default center-block">
    <div class="panel-heading panel-heading-ww">Select Your Preferred Job Categories</div>
        <div class="panel-body">
            <form method="post" action="/selected-category" id="myForm">
                {!! csrf_field() !!}
                <div class="form-group">
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
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-default" id="submitBtn" onclick="restrict()" />
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('js_plugins')
    @include('js_plugins.submit_restrict')
@stop