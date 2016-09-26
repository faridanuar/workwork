@extends('layouts.app')

@section('content')

<div class="flash">
	@include('messages.flash')
</div>

<section id="intro">
	<article>
		<p class="lead">
		@lang('messages.welcome')
		</p>
	</article>
</section>

<div class="row">
	<div class="col-md-3 hidden-xs">
		<div class="panel panel-default">
			<!-- todo: Filter -->
			<div class="panel-body">

			Filter jobs

			<div class="form-group">
			<h3>Salary:</h3>
				<div id="sort-by-container"></div>
			</div>

			<div class="form-group">
				<div id="categories"></div>
			</div>

			</div>
		</div>
	</div>


    <div class="col-md-6" id="adverts" >
    	<div class="form-group">

		    <input type="text" class="form-control" id="search-box" />

	    </div>
		<div class="results">

		    <div id="hits-container"></div>
		    <div id="pagination-container"></div>

		</div>
	</div>


	<div class="col-md-3">
		<div class="well">
			<!-- todo Jumpbar -->
			Other things, i.e. adverts, announcements.
		</div>
	</div>
</div>

@include('js_plugins.algolia')



@stop