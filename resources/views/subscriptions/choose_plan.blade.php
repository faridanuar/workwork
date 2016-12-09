@extends('layouts.app')

@section('content')
<div class="flash">
    @include('messages.flash')
</div>

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
        <form action="/choose/plan/{{ $id }}" method="post">

        {!! csrf_field() !!}

        <div class="plan-choices row">
           {{-- <!-- <div class="col-sm-6">
            
                <label>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <input type="radio" name="plan" id="plan0" value="2_Month_Plan" autocomplete="off" checked>
                                <span>60 Days - RM 49</span>
                                <hr>
                                <ul>
                                    <li>@lang('forms.plan_30day_a_feature_1')</li>
                                    <li>Unlimited SMS Notification</li>
                                    <li>Unlimited Email Notification</li>
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
                            <input type="radio" name="plan" id="plan1" value="1_Month_Plan" autocomplete="off">
                                <span>@lang('forms.plan_30day_a')</span>
                                <hr>
                                <ul>
                                    <li>@lang('forms.plan_30day_a_feature_1')</li>
                                    <li>Unlimited SMS Notification</li>
                                    <li>Unlimited Email Notification</li>
                                </ul>
                            </input>
                        </div>
                    </div>
                </label>

            </div>
            --> --}}

            <div class="col-sm-6">

                <label>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <input type="radio" name="plan" id="plan2" value="Trial" autocomplete="off">
                                <span>14 Days Trials - Free</span>
                                <hr>
                                <ul>
                                    <li>View 10 Requests</li>
                                    <li>5 SMS Notification</li>
                                    <li>Unlimited Email Notification</li>
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
                            <input type="radio" name="plan" id="plan3" value="Free" autocomplete="off">
                                <span>7 days - Free</span>
                                <hr>
                                <ul>
                                    <li>@lang('forms.plan_7day_a_feature_1')</li>
                                    <li>Unlimited Email Notification</li>
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