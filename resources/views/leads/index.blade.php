@extends('layouts.master')

@section('content')
	<div class="container">
		<h2>Call tracking</h2>
		<div class="row">
			<h3>Add a new number</h3>
			<p>Create a new lead source by purchasing a new phone number. Area code is optional</p>
			{!! Form::open(['url' => route('available_number.index'), 'method' => 'GET']) !!}
				{!! Form::label('areaCode', 'Area code: ') !!}
				{!! Form::number('areaCode') !!}
				{!! Form::submit('Search', ['class' => 'btn btn-primary btn-xs']) !!}
			{!! Form::close() !!}
			@include('lead_sources.index', ['leadSources' => $leadSources, 'appSid' => $appSid])
		</div>
		<div class="row">
			<table class="table">
				<thead>
					<th>Tracking Number</th>
					<th>Caller</th>
					<th>Status</th>
					<th>Duration</th>
					<th>Date</th>
				</thead>
				<tbody>
					@foreach ($leads as $lead)
						<tr>
							<td>
								@foreach ($leadSources as $leadSource)
									@if ($lead->lead_source_id == $leadSource->id)
										<strong>{{ $leadSource->description }}</strong>
										<br>
										{{ $leadSource->number }}
									@elseif ($lead->lead_source_id != $leadSource->id)
									@elseif (!$lead->lead_source_id)
										<strong>Tracking Number Deleted</strong>
									@endif
								@endforeach							
							</td>
							<td>
								@if (!$lead->caller_name)
									<strong><em>No Caller ID</em>, {{ $lead->city }}</strong>
								@else
									<strong>{{ $lead->caller_name }}, {{ $lead->city }}</strong>
								@endif
								<br>
								{{ $lead->caller_number }}
							</td>
							<td><span style="text-transform: capitalize">{{ $lead->status }}</span></td>	
							<td>{{ $lead->duration }}s</td>
							<td>{{ $lead->created_at }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@stop

@section('scripts')
    {!! Html::script('//cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js') !!}
    {!! Html::script('//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js')!!}
    {!! Html::script('/js/pieCharts.js') !!}
@stop
