<a href="/adverts">Back to adverts</a>
<h2>Job Requests for {{$advert->job_title}}</h2>
<hr>
<ul class="nav nav-pills">
  <li role="presentation"><a href="/advert/{{ $id }}/job/requests/all">All</a></li>
  <li role="presentation" class="active"><a href="/advert/{{ $id }}/job/requests/pending">New</a></li>
  <li role="presentation"><a href="/advert/{{ $id }}/job/requests/rejected">Rejected</a></li>
  <li role="presentation"><a href="/advert/{{ $id }}/job/requests/accepted">Accepted</a></li>
</ul>