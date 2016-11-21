@extends('layouts.app')
@section('content')
<div class="ftu-panel panel-ww-600 panel panel-default center-block">
    <div class="panel-heading panel-heading-ww">Reviews:</div>
		<div class="panel-body">
			@forelse ($userReviews as $userReview)
				<div class="form-group">
					<div>rating: {{ $userReview->rating }}</div>
			        <div>{{ $userReview->comment }}</div>
			        <div><b>Posted By:</b> {{ $userReview->postedBy }}</div>
			    </div>
			@empty
				No ratings yet
			@endforelse
			
			{!! $userReviews->render() !!}
		</div>
	</div>
</div>
@stop