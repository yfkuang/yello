@foreach ($numbers as $number)
	<tr class="number-row">
		<td class="number-number"> {{ $number->friendlyName }} </td>
		<td class="number-locality">
			@if (!$number->locality)
				<em>Not Available</em>,&nbsp;
			@else
				{{ $number->locality }},&nbsp;
			@endif
			{{ $number->region }}
		</td>
		<td>
			{!! Form::button('Purchase', ['class' => 'btn btn-primary btn-xs', 'href' => '#modalCarousel',  'data-slide' => 'next', 'value' => $number->phoneNumber])  !!}
		</td>
	</tr>
@endforeach
