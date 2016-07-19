@extends('layouts.app')

@section('content')

@foreach ($details as $detail)
<h1>{{ $detail->job_title }}</h1>
@endforeach

@stop