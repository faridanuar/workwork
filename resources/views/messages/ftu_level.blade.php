<ul class="levels">
@for($i = 0; $i < $done; $i++)
	<li class="done">Done,</li>
@endfor

	<li class="current">Current,</li>

@for($x = $notDone; $x < $done; $x++)
	<li class="notdone">Not Done,</li>
@endfor
</ul>
