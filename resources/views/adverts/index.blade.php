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

<!-- scripts for algolia API -->

<script src="https://code.jquery.com/jquery.js"></script>

<script src="https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js"></script>

<script src="https://cdn.jsdelivr.net/autocomplete.js/0/autocomplete.min.js"></script>

<script src="https://cdn.jsdelivr.net/instantsearch.js/1/instantsearch.min.js"></script>

<!-- this configuration uses Vue javascript pack -->
<script>
var itsAppID = 'DXWCE2Q6H1';
var itsApiKey = '097d939c93adfac39efd4e6c2fa45775';
var itsIndex = 'adverts';

//Initialise for autocomplete js
var client = algoliasearch(itsAppID, itsApiKey)
var index = client.initIndex(itsIndex);

//autocomplete function configurations
autocomplete('#search-box', { hint: false }, [
    {
      source: autocomplete.sources.hits(index, { hitsPerPage: 5 }),
      displayKey: 'job_title',
      templates: {
        suggestion: function(suggestion) {
          return (
          	'<div class="hits">'+
          		'<span class="job_title">' + suggestion._highlightResult.job_title.value + '</span>' +
          	'</div>'
          	);
        }
      }
    }
  ]).on('autocomplete:selected', function(event, suggestion, dataset) {
    console.log(suggestion, dataset);
  });


//initialise instant search
var search = instantsearch({
	appId: itsAppID,
	apiKey: itsApiKey,
	indexName: itsIndex,
	urlSync: true
});



//search widgets
search.addWidget(
	instantsearch.widgets.searchBox({
	  container: '#search-box',
	  placeholder: 'Search for products...',
	  searchOnEnterKeyPressOnly: false
	})
);



//hits templates
var resultsTemplate =
	'<a href="/adverts/@{{ id }}/@{{ job_title }}">' +
		'<div class="panel panel-default">' +
			'<div class="panel-heading">@{{{ _highlightResult.job_title.value }}}</div>' +
			  	'<div class="panel-body">' +

					'<div class="salary">RM@{{ salary }} per @{{ rate }}</div>' +
					'<div class="business-name">@{{{ _highlightResult.business-name.value }}}</div>' +
					'<div class="location">@{{{ _highlightResult.location.value }}}</div>' +
					'<div class="street">@{{ street }}</div>' +
					'<div class="skill">Skill: @{{{ _highlightResult.skill.value }}}</div>' +

				'</div>' +
		'</div>' +
	'</a>';

//no hits template
var noResultsTemplate =
	'<div class"noResults">'+
		'Sorry no results found...' +
	'</div>'



//display hits widget
search.addWidget(
	instantsearch.widgets.hits({
	  container: '#hits-container',
	  templates: {
	  	empty: noResultsTemplate,
	    item: resultsTemplate
	  },
	  hitsPerPage: 4
	})
);



search.addWidget(
  instantsearch.widgets.sortBySelector({
    container: '#sort-by-container',
    indices: [
      {name: 'adverts', label: 'All Salary'},
      {name: 'adverts_salary_asc', label: 'Lowest'},
      {name: 'adverts_salary_desc', label: 'Highest'}
    ]
  })
);



search.addWidget(
  instantsearch.widgets.refinementList({
    container: '#categories',
    attributeName: 'category',
    operator: 'or',
    limit: 10,
    templates: {
      header: '<h3 class="category-header">Categories</h3>'
    }
  })
);



//pagination widget
search.addWidget(
	instantsearch.widgets.pagination({
	  container: '#pagination-container',
	  maxPages: 20,

	  scrollTo: false
	})
);

//Once all the widgets have been added to the instantsearch instance, start rendering by calling start() method
search.start();

</script>



@stop