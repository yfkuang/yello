@foreach ($leadSources as $leadSource)
	<tr class="leadsources-row">
		<td> {{ $leadSource->description }} </td>
		<td> {{ $leadSource->number }} </td>
		<td> {{ $leadSource->forwarding_number }} </td>
		<td>
			{!! Form::button('Edit', ['class' => 'btn ajax-edit-number', 'data-id' => $leadSource->id, 'data-toggle' => "modal", 'data-target' => "#addNumber"]) !!}
		</td>
	</tr>
@endforeach