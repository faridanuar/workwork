@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/places.js/1/places.min.js"></script>
<form action="/values" method="post">
{!! csrf_field() !!}
<input type="text" value="Amsterdam,Washington,Sydney,Beijing,Cairo" name="tags" data-role="tagsinput" />
<input type="submit" />
</form>
@stop