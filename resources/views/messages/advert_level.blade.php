<ul class="levels">
@for($i = 0; $i < $done; $i++)
	<li>Done,</li>
@endfor
	<li>Current,</li>
@for($x = $notDone; $x < $done; $x++)
	<li>Not Done,</li>
@endfor
</ul>