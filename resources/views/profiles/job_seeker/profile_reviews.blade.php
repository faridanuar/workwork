@extends('layouts.app')

@section('content')

<h1>Review</h1>

<div class="container">
@foreach ($userReviews as $userReview)
	<div class="form-group">
		<div>rating: {{ $userReview->rating }}</div>
        <div>{{ $userReview->comment }}</div>
        <div><b>Posted By:</b> {{ $userReview->postedBy }}</div>
    </div>
@endforeach
</div>

{!! $userReviews->render() !!}

@stop