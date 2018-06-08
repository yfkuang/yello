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
				<tbody>
					<td>
						{{ $leads->count() }} Total Calls
					</td>
					<td>
						{{ $leads->where('status', 'completed')->count() }} Answered Calls
					</td>
					<td>
						{{ $leads->where('status', '!=', 'completed')->count() }} Unanswered Calls
					</td>
					<td>
						{{ round($leads->where('status', 'completed')->count()/$leads->count(), 2) }}% Answer Rate
					</td>
				</tbody>
			</table>
		</div>
		<div class="row">
			<table class="table">
				<thead>
					<th>Tracking Number</th>
					<th>Caller</th>
					<th>Status</th>
					<th>Duration</th>
					<th>
						<div class="dropdown">
						  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Date
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
							<button class="dropdown-item ajax-button" data-ajax="Today">Today</button>
							<button class="dropdown-item ajax-button" data-ajax="Past Week">Past Week</button>
							<button class="dropdown-item ajax-button" data-ajax="Past Month">Past Month</button>
							<button class="dropdown-item ajax-button" data-ajax="Past 3 Months">Past 3 Months</button>
							<button class="dropdown-item ajax-button" data-ajax="Past 6 Months">Past 6 Months</button>
							<button class="dropdown-item ajax-button" data-ajax="Past Year">Past Year</button>
						  </div>
						</div>
					</th>
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
							<td>
								@if (!$lead->status)
									No Answer
								@else	
									<span style="text-transform: capitalize">{{ $lead->status }}</span>
								@endif
							</td>	
							<td>{{ $lead->duration }}s</td>
							<td>
								@php $date = explode(" ", $lead->created_at->toDayDateTimeString()); @endphp
								<strong>{{ $date[4]." ".$date[5] }}</strong><br>
								{{ $date[0]." ".$date[1]." ".$date[2]." ".$date[3] }}
								
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@stop
