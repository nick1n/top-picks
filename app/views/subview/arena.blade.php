
<div class="form-group">
	<label for="player">Player</label>
	<input type="text" class="form-control" id="player">
</div>

<div class="form-group">
	<label for="info">Info</label>
	<textarea class="form-control" rows="2" id="info"></textarea>
</div>

<div class="form-group">
	<label for="class">Class</label>
	<input type="text" class="form-control" id="class">
</div>

<div class="checkbox">
	<label>
		<input type='checkbox' id="real" checked> Real
	</label>
</div>

<hr>

@for ($index = 0; $index < 30; ++$index)
	<form role="form">
		@include('part.pack')
	</form>
@endfor

<div class="form-group">
	<label for="wins">Wins</label>
	<input type="text" class="form-control" id="wins">
</div>

<div class="form-group">
	<label for="loses">Loses</label>
	<input type="text" class="form-control" id="loses">
</div>

<div class="form-group">
	<label for="gold">Gold</label>
	<input type="text" class="form-control" id="gold">
</div>

<div class="form-group">
	<label for="dust">Dust</label>
	<input type="text" class="form-control" id="dust">
</div>

<div class="form-group">
	<label for="packs">Packs</label>
	<input type="text" class="form-control" id="packs" value="1">
</div>

<div class="form-group">
	<label for="card">Golden Card</label>
	<input type='hidden' class="card form-control" id="card">
</div>

