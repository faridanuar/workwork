@extends('layouts.app')

@section('content')

<div class="panel-ww-login panel panel-default center-block">
    <div class="panel-heading panel-heading-ww">Yeay, You're In!</div>
    <div class="panel-body">
        <form role="form" method="POST" action="{{ url('/set') }}">
            {!! csrf_field() !!}

            <div class="form-group ww-user-type">
                <h4 class="text-center">I want to:</h4>
                <div class="btn-group btn-group-justified" data-toggle="buttons" role="group" aria-label="...">
                 <label class="btn btn-default">
                    <input type="radio" name="type" id="type0" autocomplete="off" value="employer"> Hire
                  </label>
                  <label class="btn btn-default">
                    <input type="radio" name="type" id="type1" autocomplete="off" value="job_seeker"> Work
                  </label>
                </div>
                @if ($errors->has('type'))
                    <span class="help-block text-center">
                        <strong>{{ $errors->first('type') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg btn-block btn-ww-md">
                    Let's Go!
                </button>
            </div>

        </form>

    </div>
</div>
@endsection
