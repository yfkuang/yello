@extends('layouts.master')

@section('title')
	Manage Numbers
@stop

@section('content')
<div class="table-section">
	<div class="row">
		<h1>Tracking Number</h1>
	</div>
	<div class="row">
		<table class="table" id="leadsources-table">
			<thead>
				<th>Description</th>
				<th>Number</th>
				<th>Forwarded to</th>
				<th></th>
			</thead>
			<tbody>
				@foreach ($leadSources as $leadSource)
					<tr>
						<td> {{ $leadSource->description }} </td>
						<td> {{ $leadSource->number }} </td>
						<td> {{ $leadSource->forwarding_number }} </td>
						<td>
							{!! Form::button('Edit', ['class' => 'btn', 'data-toggle' => "modal", 'data-target' => "#addNumber"]) !!}
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@stop