@extends('layouts.app')

@section('content')

<form action="/values" method="post">
{!! csrf_field() !!}
<select multiple data-role="tagsinput" id="tags" name="tags[]">
  <option value="Amsterdam">Amsterdam</option>
  <option value="Washington">Washington</option>
  <option value="Sydney">Sydney</option>
  <option value="Beijing">Beijing</option>
  <option value="Cairo">Cairo</option>
</select>
<input type="submit" />
</form>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>

@stop