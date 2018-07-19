@extends('layouts.master')

@section('title')
	Dashboard
@stop

@section('content')
	<div class="table-section">
		
		<div class="row">
			<table class="table" id="stat-table">
				<tbody>
					<td>
						<i class="fas fa-phone" style="color: #4D85FF"></i><span class="stat" id="stat-total">{{ $leads->count() }}</span><br> Total Calls
					</td>
					<td>
						<i class="fas fa-check" style="color: #26DAD2"></i><span class="stat" id="stat-answered">{{ $leads->where('status', 'completed')->count() }}</span><br> Answered Calls
					</td>
					<td>
						<i class="fas fa-times" style="color: #FD8099"></i><span class="stat" id="stat-unanswered">{{ $leads->where('status', '!=', 'completed')->count() }}</span><br> Unanswered Calls
					</td>
					<td>
						<i class="fas fa-comment-alt" style="color: #F2CC53"></i><span class="stat" id="stat-answer-rate">{{ round($leads->where('status', 'completed')->count()/$leads->count(), 2)*100 }}%</span><br> Answer Rate
					</td>
				</tbody>
			</table>
		</div>
	</div>
	<div class="table-section">
		<div class="row">
			<table class="table" id="lead-table">
				<thead>
					<th>
						<div class="dropdown">
							Tracking Number
						 	<button class="btn dropdown-toggle-split dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fas fa-caret-down"></i>
						  	</button>
						  	<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
								<input type="text" class="ajax-text" placeholder="Tracking # or description" data-token="{{ csrf_token() }}" data-filter-type="leadSourceDesc">
								@foreach($leadSources as $leadSource)
									<button class="dropdown-item ajax-button" data-token="{{ csrf_token() }}" data-filter-type="leadSource" data-filter-value="{{ $leadSource->id }}">{{ $leadSource->description }} ({{ $leadSource->number }})</button>
								@endforeach
						  	</div>
						</div>
					</th>
					<th>
						<div class="dropdown">
							Caller
							<button class="btn dropdown-toggle-split dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fas fa-caret-down"></i>
						  	</button>
							<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
								<div class="btn-group dropright">
									<button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Caller
									</button>
									<div class="dropdown-menu">
										@foreach($callers as $caller)
											<button class="dropdown-item ajax-button" data-token="{{ csrf_token() }}" data-filter-type="leadSource" data-filter-value="">{{ $caller->caller_name }} ({{ $caller->caller_number }})</button>
										@endforeach
									</div>
								</div>
								<div class="btn-group dropright">
									<button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										City
									</button>
									<div class="dropdown-menu">
										@foreach($cities as $city)
											<button class="dropdown-item ajax-button" data-token="{{ csrf_token() }}" data-filter-type="leadSource" data-filter-value="">{{ $city->city }}</button>
										@endforeach
									</div>
								</div>
							</div>
						</div>
					</th>
					<th>
						<div class="dropdown">
							Status
						  	<button class="btn dropdown-toggle-split dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fas fa-caret-down"></i>
						  	</button>
						  	<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
								<button class="dropdown-item ajax-button" data-token="{{ csrf_token() }}" data-filter-type="status" data-filter-value="completed">Completed</button>
								<button class="dropdown-item ajax-button" data-token="{{ csrf_token() }}" data-filter-type="status" data-filter-value="no-answer">No Answer</button>
						  	</div>
						</div>
					</th>
					<th>
						Duration
						<button class="btn dropdown-toggle-split dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fas fa-caret-down"></i>
						</button>
					</th>
					<th>
						<div class="dropdown">
							Date
						  	<button class="btn dropdown-toggle-split dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fas fa-caret-down"></i>
						  	</button>
						  	<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
								<button class="dropdown-item ajax-button" data-token="{{ csrf_token() }}" data-filter-type="date" data-filter-value="{{ \Carbon\Carbon::today() }}">Today</button>
								<button class="dropdown-item ajax-button" data-token="{{ csrf_token() }}" data-filter-type="date" data-filter-value="{{ \Carbon\Carbon::today()->subWeek() }}">Past Week</button>
								<button class="dropdown-item ajax-button" data-token="{{ csrf_token() }}" data-filter-type="date" data-filter-value="{{ \Carbon\Carbon::today()->subMonth() }}">Past Month</button>
								<button class="dropdown-item ajax-button" data-token="{{ csrf_token() }}" data-filter-type="date" data-filter-value="{{ \Carbon\Carbon::today()->subMonths(3) }}">Past 3 Months</button>
								<button class="dropdown-item ajax-button" data-token="{{ csrf_token() }}" data-filter-type="date" data-filter-value="{{ \Carbon\Carbon::today()->subMonths(6) }}">Past 6 Months</button>
								<button class="dropdown-item ajax-button" data-token="{{ csrf_token() }}" data-filter-type="date" data-filter-value="{{ \Carbon\Carbon::today()->subYear() }}">Past Year</button>
								<input type="date">
						  </div>
						</div>
					</th>
				</thead>
				<tbody>
					@foreach ($leads as $lead)
						<tr class="lead-row">
							<td class="lead-row-source">
									@if ($lead->description)
										<strong>{{ $lead->description }}</strong>
										<br>
										{{ $lead->number }}
									@else
										<strong>Tracking Number Deleted</strong>
									@endif								
							</td>
							<td class="lead-row-caller">
								@if (!$lead->caller_name)
									<strong><em>No Caller ID</em>, {{ ucwords(strtolower($lead->city)) }}</strong>
								@else
									<strong>{{ $lead->caller_name }}, {{ ucwords(strtolower($lead->city)) }}</strong>
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
