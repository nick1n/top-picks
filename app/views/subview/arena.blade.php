
<form role="form" data-action="{{ URL::to('arena') }}">

<div class="row">
	<div class="col-xs-5">
		<div class="form-group">
			<label for="player">Player</label>
			{{ Form::select('player', Player::allArray(), Input::old('player'), array('id' => 'player', 'class' => 'form-control')) }}
		</div>
	</div>

	<div class="col-xs-5">
		<div class="form-group">
			<label for="class">Class</label>
			{{ Form::select('class', Classification::allArray(), Input::old('class'), array('id' => 'class', 'class' => 'form-control')) }}
		</div>
	</div>
</div>

<div class="form-group">
	<label for="info">Info</label>
	<textarea class="form-control" rows="2" name="info" id="info" placeholder="Their local time they started the draft, video url, video time they started the draft, etc...">{{ Input::old('info') }}</textarea>
</div>

<div class="checkbox">
	<label>
		<input type='checkbox' name="real" id="real" {{ Input::old('real') == 'off' ? '' : 'checked' }}> <strong>Real</strong>
	</label>
</div>

<hr>

@for ($index = 0; $index < 30; ++$index)
	@include('part.pack')
@endfor

<div class="row">
	<div class="col-sm-2 col-xs-6">
		<div class="form-group">
			<label for="wins">Wins</label>
			<input type="number" min="0" max="9" step="1" maxlength="1" class="form-control" name="wins" id="wins" value="{{ Input::old('wins') }}">
		</div>
	</div>

	<div class="col-sm-2 col-xs-6">
		<div class="form-group">
			<label for="loses">Loses</label>
			<input type="number" min="0" max="3" step="1" maxlength="1" class="form-control" name="loses" id="loses" value="{{ Input::old('loses') }}">
		</div>
	</div>

	<div class="col-sm-3 col-xs-4">
		<div class="form-group">
			<label for="gold">Gold</label>
			<input type="number" min="0" max="500" step="5" maxlength="3" class="form-control" name="gold" id="gold" value="{{ Input::old('gold') }}">
		</div>
	</div>

	<div class="col-sm-3 col-xs-4">
		<div class="form-group">
			<label for="dust">Dust</label>
			<input type="number" min="0" max="500" step="5" maxlength="3" class="form-control" name="dust" id="dust" value="{{ Input::old('dust') }}">
		</div>
	</div>

	<div class="col-sm-2 col-xs-4">
		<div class="form-group">
			<label for="packs">Packs</label>
			<input type="number" min="1" max="2" step="1" maxlength="1" class="form-control" name="packs" id="packs" value="{{ Input::old('packs') ?: 1 }}">
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-6">
		<div class="form-group">
			<label for="card">Golden Card</label>
			<input type='hidden' data-filter="false" class="card form-control" name="card0" id="card0" value="{{ Input::old('card0') }}">
		</div>
	</div>

	<div class="col-sm-6">
		<div class="form-group">
			<label for="card">Golden Card</label>
			<input type='hidden' data-filter="false" class="card form-control" name="card1" id="card1" value="{{ Input::old('card1') }}">
		</div>
	</div>
</div>

{{ Form::token() }}

<br>

<button type="submit" class="btn btn-primary btn-lg btn-block">Done</button>

</form>

<br><br><br><br><br>
