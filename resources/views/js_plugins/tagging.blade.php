@extends('layouts.app')

@section('content')

<form action="/values" method="post">
{!! csrf_field() !!}
<select  name="tags" multiple data-role="tagsinput">
  <option value="IT">IT</option>
  <option value="Food">Food</option>
<input type="submit" />
</form>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
<script>
$('tags').tagsinput({
    itemValue: 'id'
});
</script>

@stop