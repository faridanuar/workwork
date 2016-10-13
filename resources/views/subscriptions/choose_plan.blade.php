@extends('layouts.app')

@section('content')

@if($user->ftu_level < 4)
	@include('messages.ftu_level')
@else
	@include('messages.advert_level')
@endif

<h1 class="ftu-intro">@lang('ftu.choose_plan')</h1>
<div class="ftu-panel panel-ww-600 panel panel-default center-block">
    <div class="ftu-arrow"></div>
    <div class="panel-heading panel-heading-ww">@lang('forms.plan_title')</div>
    <div class="panel-body">
        <form action="/checkout/{{ $id }}" method="post">

        {!! csrf_field() !!}

        <div class="plan-choices row">
            <div class="col-sm-6">

                <label>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <input type="radio" name="plan" id="plan1" value="1_Month_Plan" autocomplete="off" checked>
                                <span>@lang('forms.plan_30day_a')</span>
                                <hr>
                                <ul>
                                    <li>@lang('forms.plan_30day_a_feature_1')</li>
                                </ul>
                            </input>
                        </div>
                    </div>
                </label>

            </div>

            <div class="col-sm-6">

                <label>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <input type="radio" name="plan" id="plan0" value="Trial" autocomplete="off">
                                <span>@lang('forms.plan_7day_a')</span>
                                <hr>
                                <ul>
                                    <li>@lang('forms.plan_7day_a_feature_1')</li>
                                </ul>
                            </input>
                        </div>
                    </div>
                </label>

            </div>
        </div>

        <div class="form-group">
        	<button type="submit" class="btn btn-primary btn-lg btn-block btn-ww-lg">@lang('forms.plan_next_pay')<span class="btn-arrow">&#8594;</span></button>
        </div>

        </form>
    </div>
</div>
@stop