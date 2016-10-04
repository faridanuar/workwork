<script src="https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js"></script>

<script src="https://cdn.jsdelivr.net/autocomplete.js/0/autocomplete.min.js"></script>

<script src="https://cdn.jsdelivr.net/instantsearch.js/1/instantsearch.min.js"></script>

<!-- this configuration uses algolia javascript plugin -->
<script>
var itsAppID = '{{ $id }}';
var itsApiKey = '{{ $api }}';
var itsIndex = '{{ $index }}';
var salaryASC = '{{ $index_asc }}';
var salaryDESC = '{{ $index_desc }}';

//Initialise for autocomplete js
var client = algoliasearch(itsAppID, itsApiKey)
var index = client.initIndex(itsIndex);

//autocomplete function configurations
autocomplete('#search-box', { hint: false }, [
    {
      source: autocomplete.sources.hits(index, { hitsPerPage: 6 }),
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
	urlSync: true,
  searchParameters: {
    facetsRefinements: {
      group: ['All']
    },
    disjunctiveFacetsRefinements: {
      category: ['Others']
    },
    // Add to "facets" all attributes for which you
    // do NOT have a widget defined
    facets: ['group']
  },
});



//search widgets
search.addWidget(
	instantsearch.widgets.searchBox({
	  container: '#search-box',
	  placeholder: '@lang("adverts.search_for")',
	  searchOnEnterKeyPressOnly: false
	})
);



//hits templates
var resultsTemplate =
	'<a class="panel-job-links" href="/adverts/@{{ id }}/@{{ job_title }}">' +
		'<div class="panel panel-default">' +
		  	'<div class="panel-body">' +
                '<div class="business-name">@{{{ _highlightResult.business_name.value }}}</div>' +
                '<div class="job-title">@{{{ _highlightResult.job_title.value }}}</div>' +
				'<div class="salary"><div class="amount"><sup class="currency">RM</sup>@{{ salary }} </div>' +
                    '<div class="rate"> <span class="per">per</span> <span>@{{ rate }}</span></div> </div>' +
				'<div class="location hidden">@{{{ _highlightResult.location.value }}}</div>' +
				'<div class="street hidden">@{{ street }}</div>' +
				'<div class="skill hidden">Skill: @{{{ _highlightResult.skill.value }}}</div>' +
        '<img src="@{{ avatar }}" class="avatar-tn" height="30" width="30" />' +
			'</div>' +
		'</div>' +
	'</a>';

//no hits template
var noResultsTemplate =
	'<div class"noResults">'+
		'@lang("adverts.no_results")' +
	'</div>';



//display hits widget
search.addWidget(
	instantsearch.widgets.hits({
	  container: '#hits-container',
	  templates: {
	  	empty: noResultsTemplate,
	    item: resultsTemplate
	  },
	  hitsPerPage: 20
	})
);



search.addWidget(
  instantsearch.widgets.sortBySelector({
    container: '#sort-by-container',
    indices: [
      {name: itsIndex, label: '@lang("adverts.all")'},
      {name: salaryASC, label: '@lang("adverts.lowest")'},
      {name: salaryDESC, label: '@lang("adverts.highest")'}
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
      header: '<h3 class="category-header">@lang("adverts.category")</h3>'
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