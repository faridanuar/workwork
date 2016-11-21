@extends('layouts.app')

@section('content')

<style>

.table-invoice td{

	padding: 10px 10px;
}

.table-invoice th{

    padding: 10px 10px;
}

</style>

<div class="ftu-panel panel-ww-600 panel panel-default center-block">
    <div class="panel-heading panel-heading-ww">Invoices</div>
        <div class="panel-body">
            <table border="1" class="table-invoice">
            		<tr>
                    	<th>Invoice ID</th>
                    	<!-- <th>Subscription Plan</th> -->
                        <th>Date Purchased</th>
                        <th>Amount</th>
                        <th>Download PDF</th>
                    </tr>
                @foreach ($invoices as $invoice)
                    <tr>
                    	<td>{{ $invoice->id  }}</td>
                    	<!-- <td>{{ $invoice->planId  }}</td> -->
                        <td>{{ $invoice->date()->toFormattedDateString() }}</td>
                        <td>{{ $invoice->total() }}</td>
                        <td><a class="btn btn-default btn-xs" href="/invoices/download/{{ $invoice->id }}">Download</a></td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>

@stop