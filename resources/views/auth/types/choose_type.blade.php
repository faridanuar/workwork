@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">What are you looking for?</div>
                <div class="panel-body">

                    <form role="form" method="POST" action="{{ url('/set') }}">
                        {!! csrf_field() !!}

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3">
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-primary">
                                        <input type="radio" name="type" id="type0" autocomplete="off" value="employer"> Hire
                                    </label>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="type" id="type1" autocomplete="off" value="job_seeker"> Work
                                    </label>
                                </div>
                            </div>
                        </div>

                        

                        <div class="form-group">
                            <div class="col-md-7 col-md-offset-2">
                            <hr>
                                <button type="submit" class="btn btn-success btn-block">
                                    <i class="fa fa-btn fa-sign-in">Submit</i>
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
