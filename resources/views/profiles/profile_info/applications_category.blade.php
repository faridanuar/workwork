<a href="/dashboard">Back</a>

<div>
<a href="/my/applications" id="urlPending">Pending List</a> ||
<a href="/my/applications/rejected" id="urlRejected">Rejected List
@if(count($jobSeeker->applications
			->where('status', 'REJECTED')
			->where('viewed', 0)) > 0)

		{{ count($jobSeeker->applications
				->where('status', 'REJECTED')
				->where('viewed', 0)) }} 
	@endif
</a> || 
<a href="/my/applications/accepted" id="urlAccepted">Accepted List 
	@if(count($jobSeeker->applications
			->where('status', 'ACCEPTED FOR INTERVIEW')
			->where('viewed', 0)) > 0)

		{{ count($jobSeeker->applications
				->where('status', 'ACCEPTED FOR INTERVIEW')
				->where('viewed', 0)) }} 
	@endif
</a>
</div>