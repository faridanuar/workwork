@extends('layouts.app')

@section('content')

<style>

.table-invoice td{

	padding: 0px 10px;
}

</style>

<table border="1" class="table-invoice">
		<tr>
        	<th>Invoice ID</th>
        	<th>Subscription Plan</th>
            <th>Date Purchased</th>
            <th>Amount</th>
            <th>Download PDF</th>
        </tr>
    @foreach ($invoices as $invoice)
        <tr>
        	<td>{{ $invoice->id  }}</td>
        	<td>{{ $invoice->planId  }}</td>
            <td>{{ $invoice->date()->toFormattedDateString() }}</td>
            <td>{{ $invoice->total() }}</td>
            <td><a href="/invoices/download/{{ $invoice->id }}">Download</a></td>
        </tr>
    @endforeach
</table>

@stop