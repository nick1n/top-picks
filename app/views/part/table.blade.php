
<table class="table table-striped table-hover">

	<thead>
		<tr>

		@foreach ($array[0] as $key => $value)
			<th>{{ $key }}</th>
		@endforeach

		</tr>
	</thead>

	<tbody>

	@for ($index = 0; $index < count($array); ++$index)
		<tr>

		@foreach ($array[$index] as $value)
			<td>{{ $value }}</td>
		@endforeach

		</tr>
	@endfor

	</tbody>

</table>
