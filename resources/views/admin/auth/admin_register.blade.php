@extends('layouts.app')
@section('content')
<div class="panel-ww-registration panel panel-default center-block">
    <div class="panel-heading panel-heading-ww">Sign Up</div>
    <div class="panel-body">
        <form role="form" method="POST" action="{{ url('/a/primary/register') }}" id="myForm">
            {!! csrf_field() !!}

            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label>Your Name</label>
                <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Joe Black">

                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label>Your Email</label>
                <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="joe@joeblack.com">

                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label>Password</label>
                <input type="password" class="form-control" name="password" placeholder="6+ characters or more">

                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                <label>Confirm password</label>
                <input type="password" class="form-control" name="password_confirmation">

                @if ($errors->has('password_confirmation'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span>
                @endif
            </div>


            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg btn-block btn-ww-md" 
                id="submitBtn" onclick="restrict()">
                    Sign up
                </button>
            </div>
            <p class="text-center">By signing up, I agree to Workwork's <a href="">Terms and Conditions</a> and <a href="">Privacy Policy</a></p>
        </form>
        <hr>
        <div class="sign-in__no-account">Already have a Workwork account? <a class="btn btn-default pull-right" href="{{ url('/a/login') }}">Log in</a></div>
    </div>
</div>
@endsection
@section('js_plugins')
    @include('js_plugins.submit_restrict')
@stop
