@extends('layouts.app')

@section('content')

<div class="flash">
	@include('messages.flash')
</div>

<h1>Part-time Jobs</h1>

<div class="row">
	<div class="col-md-3">
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
		<div class="panel panel-default">
			<!-- todo Jumpbar -->
			<div class="panel-body">
			Other things, i.e. adverts, announcements.
			</div>
		</div>
	</div>
</div>

@include('js_plugins.algolia')



@stop