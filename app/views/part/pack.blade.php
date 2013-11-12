
<h3>{{ $index + 1 }}:</h3>

<div class="row">
	<div class="col-sm-4">
		<input type='hidden' name="card-{{ $index }}-0" class="card form-control" value="{{ Input::old("card-$index-0") }}">
	</div>

	<div class="col-sm-4">
		<input type='hidden' name="card-{{ $index }}-1" class="card form-control" value="{{ Input::old("card-$index-1") }}">
	</div>

	<div class="col-sm-4">
		<input type='hidden' name="card-{{ $index }}-2" class="card form-control" value="{{ Input::old("card-$index-2") }}">
	</div>
</div>

<div class="row">
	<div class="col-xs-4">
		<div class="radio">
			<label>
				<input type='radio' name="pick-{{ $index }}" value="0" {{ Input::old("pick-$index") == '0' ? 'checked' : '' }}> Pick
			</label>
		</div>
	</div>

	<div class="col-xs-4">
		<div class="radio">
			<label>
				<input type='radio' name="pick-{{ $index }}" value="1" {{ Input::old("pick-$index") == '1' ? 'checked' : '' }}> Pick
			</label>
		</div>
	</div>

	<div class="col-xs-4">
		<div class="radio">
			<label>
				<input type='radio' name="pick-{{ $index }}" value="2" {{ Input::old("pick-$index") == '2' ? 'checked' : '' }}> Pick
			</label>
		</div>
	</div>
</div>

<hr>
