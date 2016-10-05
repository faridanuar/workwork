<!-- this configuration uses algolia javascript plugin -->
<script>
var itsAppID = 'DXWCE2Q6H1';
var itsApiKey = '2f0e4f4b170e55a09e152a2d9677734a';
var itsIndex = 'adverts';

//Initialise for autocomplete js
var client = algoliasearch(itsAppID, itsApiKey)
var index = client.initIndex(itsIndex);

//autocomplete function configurations
autocomplete('#global-search-box', { hint: false }, [
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
    window.location = 'http://workwork.app/adverts/' + suggestion.id + '/' + suggestion.job_title;
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
	  container: '#global-search-box',
	  placeholder: '@lang("adverts.search_for")',
	  searchOnEnterKeyPressOnly: false
	})
);

//Once all the widgets have been added to the instantsearch instance, start rendering by calling start() method
search.start();
</script>