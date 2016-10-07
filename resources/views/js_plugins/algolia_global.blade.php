<?php
$config = config('services.algolia');
$id = $config['app_id'];
$api = $config['search_key'];
$index = $config['index'];
$index_asc = $config['index_asc'];
$index_desc = $config['index_desc'];
$url = $config['site_url'];  
?>

<!-- this configuration uses algolia javascript plugin -->
<script>
var itsAppID = '{{ $id }}';
var itsApiKey = '{{ $api }}';
var itsIndex = '{{ $index }}';
var salaryASC = '{{ $index_asc }}';
var salaryDESC = '{{ $index_desc }}';
var url = '{{ $url }}';

//Initialise for autocomplete js
var client = algoliasearch(itsAppID, itsApiKey)
var index = client.initIndex(itsIndex);

//autocomplete function configurations
autocomplete('#global-search', { hint: false }, [
    {
      source: autocomplete.sources.hits(index, { hitsPerPage: 6 }),
      displayKey: 'job_title',
      templates: {
        suggestion: function(suggestion) {
          return (
          	'<div class="hits">' +
          		'<span class="job_title">' + suggestion._highlightResult.job_title.value + '</span>' +
          	'</div>'
          );
        }
      }
    }
  ]).on('autocomplete:selected', function(event, suggestion, dataset) {
    window.location = url + 'adverts/' suggestion.id + '/' + suggestion.job_title;
    console.log(suggestion, dataset);
  });


  //initialise instant search
  var search = instantsearch({
    appId: itsAppID,
    apiKey: itsApiKey,
    indexName: itsIndex,
    urlSync: true,
  });


//search widgets
search.addWidget(
	instantsearch.widgets.searchBox({
	  container: '#global-search',
	  placeholder: '@lang("adverts.search_for")',
	  searchOnEnterKeyPressOnly: false
	})
);

//Once all the widgets have been added to the instantsearch instance, start rendering by calling start() method
search.start();
</script>