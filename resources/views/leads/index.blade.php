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
						<span id="stat-total">{{ $leads->count() }}</span> Total Calls
					</td>
					<td>
						<span id="stat-answered">{{ $leads->where('status', 'completed')->count() }}</span> Answered Calls
					</td>
					<td>
						<span id="stat-unanswered">{{ $leads->where('status', '!=', 'completed')->count() }}</span> Unanswered Calls
					</td>
					<td>
						<span id="stat-answer-rate">{{ round($leads->where('status', 'completed')->count()/$leads->count(), 2)*100 }}%</span> Answer Rate
					</td>
				</tbody>
			</table>
		</div>
		<div class="row">
			<table class="table" id="lead-table">
				<thead>
					<th>
						<div class="dropdown">
						  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Tracking Number
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
								@foreach($leadSources as $leadSource)
									<button class="dropdown-item ajax-button" data-token="{{ csrf_token() }}" data-filter-type="leadSource" data-filter-value="{{ $leadSource->id }}">{{ $leadSource->description }} ({{ $leadSource->number }})</button>
							  	@endforeach
						  </div>
						</div>
					</th>
					<th>Caller</th>
					<th>
						<div class="dropdown">
						  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Status
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
							<button class="dropdown-item ajax-button" data-token="{{ csrf_token() }}" data-filter-type="status" data-filter-value="completed">Completed</button>
							<button class="dropdown-item ajax-button" data-token="{{ csrf_token() }}" data-filter-type="status" data-filter-value="no-answer">No Answer</button>
						  </div>
						</div>
					</th>
					<th>
						Duration
					</th>
					<th>
						<div class="dropdown">
						  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Date
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
							<button class="dropdown-item ajax-button" data-token="{{ csrf_token() }}" data-filter-type="date" data-filter-value="{{ \Carbon\Carbon::today() }}">Today</button>
							<button class="dropdown-item ajax-button" data-token="{{ csrf_token() }}" data-filter-type="date" data-filter-value="{{ \Carbon\Carbon::today()->subWeek() }}">Past Week</button>
							<button class="dropdown-item ajax-button" data-token="{{ csrf_token() }}" data-filter-type="date" data-filter-value="{{ \Carbon\Carbon::today()->subMonth() }}">Past Month</button>
							<button class="dropdown-item ajax-button" data-token="{{ csrf_token() }}" data-filter-type="date" data-filter-value="{{ \Carbon\Carbon::today()->subMonths(3) }}">Past 3 Months</button>
							<button class="dropdown-item ajax-button" data-token="{{ csrf_token() }}" data-filter-type="date" data-filter-value="{{ \Carbon\Carbon::today()->subMonths(6) }}">Past 6 Months</button>
							<button class="dropdown-item ajax-button" data-token="{{ csrf_token() }}" data-filter-type="date" data-filter-value="{{ \Carbon\Carbon::today()->subYear() }}">Past Year</button>
						  </div>
						</div>
					</th>
				</thead>
				<tbody>
					@foreach ($leads as $lead)
						<tr class="lead-row">
							<td class="lead-row-source">
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
							<td class="lead-row-caller">
								@if (!$lead->caller_name)
									<strong><em>No Caller ID</em>, {{ $lead->city }}</strong>
								@else
									<strong>{{ $lead->caller_name }}, {{ $lead->city }}</strong>
								@endif
								<br>
								{{ $lead->caller_number }}
							</td>
							<td class="lead-row-status">
								@if (!$lead->status)
									No Answer
								@else	
									<span style="text-transform: capitalize">{{ $lead->status }}</span>
								@endif
							</td>	
							<td class="lead-row-duration">{{ $lead->duration }}s</td>
							<td class="lead-row-date">
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
