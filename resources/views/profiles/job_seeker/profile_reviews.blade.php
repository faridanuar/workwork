@extends('layouts.app')
@section('content')
<div class="ftu-panel panel-ww-600 panel panel-default center-block">
    <div class="panel-heading panel-heading-ww">Reviews:</div>
    	@forelse ($userReviews as $userReview)
			<div class="panel-body">
				<div class="form-group">
					<div>rating: {{ $userReview->rating }}</div>
			        <div>{{ $userReview->comment }}</div>
			        <div><b>Posted By:</b> {{ $userReview->postedBy }}</div>
			    </div>
			</div>
		@empty
			<div class="form-group">
				No ratings yet
			</div>
		@endforelse
	</div>
</div>

{!! $userReviews->render() !!}
@stop