@extends('layouts.app')

@section('content')

<div class="flash">
	@include('messages.flash')
</div>

<section id="intro">
	<div class="ww-banner">
	</div>
	<!-- <article>
		<p class="lead">
		@lang('messages.welcome')
		</p>
	</article> -->
</section>

<div class="row">
	<div class="col-md-3 hidden-xs">
		<div id="filter-tool" class="panel panel-default">
			<div class="panel-body">
				<div class="title">@lang('adverts.filter')</div>
				<div id="sort-by-container" class="panel-section"></div>
				<div id="categories" class="panel-section"></div>
				<div id="clear-all" class="panel-bottom-action"></div>
			</div>
		</div>
	</div>


    <div class="col-md-6" id="adverts" >
    	<div class="form-group">

		    <input type="text" class="form-control" id="search-box" />

	    </div>
		<div class="results" id="results">

		    <div id="hits-container"></div>
		    <center><div id="pagination-container"></div></center>

		</div>
	</div>


	<div class="col-md-3">
		<div class="well">
			<!-- todo Jumpbar -->
			@lang('marketing.welcome')
		</div>
	</div>
</div>
@stop

@section('js_plugins')
	@include('js_plugins.algolia')
@stop