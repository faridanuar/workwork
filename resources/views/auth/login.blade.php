@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading panel-heading-ww">Login</div>
                <div class="panel-body">

                    <a class="btn btn-block btn-ww-fb" href="redirect"><span class="wwicon" aria-hidden="true"><img src="/images/icon-fb.png" alt="">&nbsp;</span>Log in with Facebook</a>
                    <div class="signup-or-separator">
                        <span class="h6 signup-or-separator--text">or</span>
                        <hr>
                    </div>
                    <form role="form" method="POST" action="{{ url('/login') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="control-label">Email Address</label>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}">

                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="control-label">Password</label>
                            <input type="password" class="form-control" name="password">

                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="remember"> Remember Me
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg btn-block btn-ww-md">
                                <i class="fa fa-btn fa-sign-in"></i>Login
                            </button>
                            <div class="text-center"><a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot password?</a></div>
                            <hr>
                            <div class="sign-in__no-account">No account? <a class="btn btn-default pull-right" href="{{ url('/register') }}">Sign up</a></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
