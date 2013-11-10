
<form role="form" action="{{ URL::to('arena') }}" method="post" enctype="multipart/form-data">

<div class="row">
	<div class="col-xs-5">
		<div class="form-group">
			<label for="player">Player</label>
			{{ Form::select('player', Player::allArray(), 1, array('id' => 'player', 'class' => 'form-control')) }}
		</div>
	</div>

	<div class="col-xs-5">
		<div class="form-group">
			<label for="class">Class</label>
			{{ Form::select('class', Classification::allArray(), 1, array('id' => 'class', 'class' => 'form-control')) }}
		</div>
	</div>
</div>

<div class="form-group">
	<label for="info">Info</label>
	<textarea class="form-control" rows="2" name="info" id="info" placeholder="Their local time they started the draft, video url, video time they started the draft, etc..."></textarea>
</div>

<div class="checkbox">
	<label>
		<input type='checkbox' name="real" id="real" checked> <strong>Real</strong>
	</label>
</div>


<hr>

@for ($index = 0; $index < 30; ++$index)
	@include('part.pack')
@endfor


<div class="row">
	<div class="col-lg-1 col-sm-2 col-xs-6">
		<div class="form-group">
			<label for="wins">Wins</label>
			<input type="number" class="form-control" name="wins" id="wins">
		</div>
	</div>

	<div class="col-lg-1 col-sm-2 col-xs-6">
		<div class="form-group">
			<label for="loses">Loses</label>
			<input type="number" class="form-control" name="loses" id="loses">
		</div>
	</div>

	<div class="col-lg-2 col-xs-4">
		<div class="form-group">
			<label for="gold">Gold</label>
			<input type="number" class="form-control" name="gold" id="gold">
		</div>
	</div>

	<div class="col-lg-2 col-xs-4">
		<div class="form-group">
			<label for="dust">Dust</label>
			<input type="number" class="form-control" name="dust" id="dust">
		</div>
	</div>

	<div class="col-lg-1 col-sm-2 col-xs-4">
		<div class="form-group">
			<label for="packs">Packs</label>
			<input type="number" class="form-control" name="packs" id="packs" value="1">
		</div>
	</div>

	<div class="col-sm-5">
		<div class="form-group">
			<label for="card">Golden Card</label>
			<input type='hidden' data-filter="false" class="card form-control" name="card" id="card">
		</div>
	</div>
</div>

{{ Form::token() }}

<button type="submit" class="btn btn-primary btn-lg btn-block">Done</button>

</form>

<br><br><br><br><br>
