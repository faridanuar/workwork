@extends('layouts.app')

@section('content')


<div class="flash">
	@include('messages.flash')
</div>


<div class="ftu-panel panel-ww-600 panel panel-default center-block">
    <div class="panel-heading panel-heading-ww">{{ $company->business_name }}</div>
		<div class="panel-body">
				@if ($average)
					{{ $average }} out of 5 STAR
				@else
					No ratings yet
				@endif
			</p>

			<hr>

			<img id="business_logo" src="{{ $photo }}" height="100" width="100"/> 
			
			@can('view_admin_features')
				@if($authorize === true)
					<a href="a/logo" class="btn btn-default btn-sm">Add Logo</a>
				@endif
			@endcan

			<hr>

			<div>
				<p>{!! nl2br(e($company->company_intro)) !!}</p>
			</div>

			<hr>

			<div>
				<div>
					{{ $company->business_category }}
				</div>

				<div>
					{{ $company->business_contact }}
				</div>

				<div>
					{{ $company->business_website }}
				</div>

				<hr>

				<div>
					Company Address:
				</div>

				<div>
					{{ $company->location }}
				</div>

				<div>
					{{ $company->street }}
				</div>

				<div>
					{{ $company->city }}
				</div>

				<div>
					{{ $company->zip }}
				</div>

				<div>
					{{ $company->state }}
				</div>
			</div>

			@can('view_admin_features')
				@if($authorize === true)
					<hr>

					<div>
						<a href="/a/company/edit" class="btn btn-default">Edit</a>
					</div>
				@endif
			@endcan

		</div>
	</div>
</div>

@stop