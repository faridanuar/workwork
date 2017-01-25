<div class="flash">
	@include('messages.flash')
</div>

<div class="form-group">
	<a href="/a/dashboard" class="btn btn-default btn-sm">Back</a>
</div>

<div>Job Requests for {{$advert->job_title}}:</div>

<div class="panel panel-default">
	<div class="panel-body">
		<ul class="nav nav-pills">
		  <li role="presentation"><a href="/a/advert/{{ $id }}/job/requests/all">All</a></li>
		  <li role="presentation"><a href="/a/advert/{{ $id }}/job/requests/pending">New</a></li>
		  <li role="presentation"><a href="/a/advert/{{ $id }}/job/requests/rejected">Rejected</a></li>
		  <li role="presentation"><a href="/a/advert/{{ $id }}/job/requests/accepted">Accepted</a></li>
		</ul>
	</div>
</div>