@extends('layouts.app')

@section('content')

<table border="1">
    @foreach ($invoices as $invoice)
        <tr>
            <td>{{ $invoice->date()->toFormattedDateString() }}</td>
            <td>{{ $invoice->total() }}</td>
            <td><a href="/invoices/download/{{ $invoice->id }}">Download</a></td>
        </tr>
    @endforeach
</table>

@stop