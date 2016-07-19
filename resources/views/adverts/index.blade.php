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

			</div>
		</div>
	</div>

    <div class="col-md-6" id="adverts" >

	    <div class="form-group">
			<input id="typeahead" class="form-control search"  type="text" v-model="query" v-on=" keyup: search ">
		</div>
		
		<div class="allAdvert" v-show="">

		<!-- 
		Replace ' ' with '-' 
		todo: remove special characters
		-->

		<!-- @foreach ($adverts as $advert)

		

		 <a href="/adverts/{{ $advert->id }}/{{ strtolower(str_replace(' ','-',$advert->job_title)) }}">
			<div class="panel panel-default">
			  	<div class="panel-heading">{{ $advert->job_title }}</div>
			  	<div class="panel-body">		
					<div class="salary">RM {{ $advert->salary }} per hour</div>
					<div class="business-name">{{ $advert->business_name }}</div>
					<div class="location">{{ $advert->location }}</div>
					<div class="street">{{ $advert->street }}</div>

				</div>
			</div>
		</a>

		@endforeach

		{!! $adverts->render() !!} -->

		</div>



		<div class="results" v-show="">

			<article v-repeat="user: users">

				<a href="/adverts/@{{ user.id }}/@{{ user.job_title }}">
					<div class="panel panel-default">

						  	<div class="panel-heading">
							  	<span v-html="user._highlightResult.job_title.value"></span>
						  	</div>

					  	<div class="panel-body">		
							<div class="salary">
							RM <span v-html="user.salary"></span> per hour
							</div>
							<div class="business-name">
								<span v-html="user._highlightResult.business_name.value"></span>
							</div>
							<div class="location">
								<span v-html="user.location"></span>
							</div>
							<div class="street">
								<span v-html="user.street"></span>
							</div>
						</div>

					</div>
				</a>

			</article>

		</div>

		<!-- <div class="error" v-show="notFound == true">
			<span v-html="noResults"></span>
		</div> -->
		
			
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

<script src="https://cdn.jsdelivr.net/typeahead.js/0.11.1/typeahead.jquery.min.js"></script>

<script src="https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/0.12.1/vue.js"></script>

<!-- this configuration uses Vue javascript pack -->
<script>

	new Vue({

		el: 'body',

		data: {

			query: '',
			users: [],
			filled: '',
			notFound: '',
			noResults: ''
			},


		ready: function()
		{
			this.client = algoliasearch('DXWCE2Q6H1','097d939c93adfac39efd4e6c2fa45775');

			this.index = this.client.initIndex('adverts');


			//select input id (#typehead) to run the configured function
			$('#typeahead')

				//set hint: false to remove autofill in input box
				.typeahead({ hint: false }, {

					source: this.index.ttAdapter(),

					displayKey: 'job_title',

					templates: {

						suggestion: function(hit) {

							return (

								'<div>' +

									'<span class="job_title">' + hit._highlightResult.job_title.value + ' </span>' +

									'<span class="business_name">(' + hit._highlightResult.business_name.value + '), </span>' +

									'<span class="location">' + hit._highlightResult.location.value + '</span>' +

									'<h4 class="skill">Skill: ' + hit._highlightResult.skill.value + ' </h4>' +

									'<span class="category">(' + hit._highlightResult.category.value + ')</span>' +

								'</div>'

							);
						}

					}

			})

			.on('typeahead:select', function(e, suggestion){

				this.query = suggestion.job_title;

			}.bind(this));


		},

		methods: 
		{
			search: function()
			{

					this.$log('query');

					this.$log('users.length');

					
					this.index.search(this.query, function(error, results){

						this.users = results.hits;

					}.bind(this));

				
			}


		}

	});

</script>



@stop