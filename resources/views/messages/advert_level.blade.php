<ul class="levels">
@for($i = 0; $i < $done; $i++)
	<li class="level-done">
        <div class="dot"></div>
    </li>
@endfor
	<li class="level-current">
        <div class="dot"></div>
    </li>
@for($x = $notDone; $x < $done; $x++)
	<li class="level-nope">
        <div class="dot"></div>
    </li>
@endfor
</ul>