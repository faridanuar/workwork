@extends('layouts.app')

@section('content')

<div class="ftu-panel panel-ww-600 panel panel-default center-block">
    <div class="panel-heading panel-heading-ww">Activity History</div>

    @forelse( $activities as $activity)
		<div class="panel-body">
			<p>Time:  {{ $activity->created_at }}</p>
        	<p>Action:  {{ $activity->activity }}</p>
        	<p>Table related: {{ $activity->table }}</p>
        	<p>User: {{ $activity->user }}</p>
        	<p>Description:<br /> {{ $activity->description }} </p>
        	<hr />
		</div>
    @empty
		<div class="panel-body">
			No Record Available
		</div>
    @endforelse
    	<center>{{ $activities->links() }}</center>
</div>

@stop

@section('js_plugins')

@stop