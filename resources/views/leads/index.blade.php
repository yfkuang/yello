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
					<th class="lead-row-source">
						<div class="dropdown">
						 	<button class="btn dropdown-toggle" data-toggle="dropdown">
								Tracking Number <i class="fas fa-caret-down"></i>
						  	</button>
						  	<div class="dropdown-menu">
								<input type="text" class="ajax-text" placeholder="Tracking # or description" data-token="{{ csrf_token() }}" data-filter-type="leadSourceDesc">
								@foreach($leadSources as $leadSource)
									<button class="ajax-button" data-token="{{ csrf_token() }}" data-filter-type="leadSource" data-filter-value="{{ $leadSource->id }}">{{ $leadSource->description }} ({{ $leadSource->number }})</button>
								@endforeach
						  	</div>
						</div>
					</th>
					<th>
						<div class="dropdown">
							<button class="btn dropdown-toggle" data-toggle="dropdown">
								Caller <i class="fas fa-caret-down"></i>
						  	</button>
							<div class="dropdown-menu custom-dropdown">
								<div class="accordion">
									<button class="btn" data-toggle="collapse" data-target="#collapse1">Caller</button>
									<div class="collapse" id="collapse1" data-toggle="collapse">
										@foreach($callers as $caller)
											<button class="ajax-button" data-token="{{ csrf_token() }}" data-filter-type="leadSource" data-filter-value="">{{ $caller->caller_name }} ({{ $caller->caller_number }})</button>
										@endforeach
									</div>
									<button class="btn" data-toggle="collapse" data-target="#collapse2">City</button>
									<div class="collapse" id="collapse2">
										@foreach($cities as $city)
											@if (!$city->city)
												<button class="ajax-button" data-token="{{ csrf_token() }}" data-filter-type="leadSource" data-filter-value="">Unknown</button>
											@else
												<button class="ajax-button" data-token="{{ csrf_token() }}" data-filter-type="leadSource" data-filter-value="">{{ ucwords(strtolower($city->city)) }}</button>
											@endif
										@endforeach
									</div>
								</div>
							</div>
						</div>
					</th>
					<th>
						<div class="dropdown">
						  	<button class="btn dropdown-toggle" data-toggle="dropdown">
								Status <i class="fas fa-caret-down"></i>
						  	</button>
						  	<div class="dropdown-menu">
								<button class="ajax-button" data-token="{{ csrf_token() }}" data-filter-type="status" data-filter-value="completed">Completed</button>
								<button class="ajax-button" data-token="{{ csrf_token() }}" data-filter-type="status" data-filter-value="no-answer">No Answer</button>
						  	</div>
						</div>
					</th>
					<th>
						<button class="btn dropdown-toggle" data-toggle="dropdown">
							Duration <i class="fas fa-caret-down"></i>
						</button>
					</th>
					<th>
						<div class="dropdown">
						  	<button class="btn dropdown-toggle" data-toggle="dropdown">
								Date<i class="fas fa-caret-down"></i>
						  	</button>
						  	<div class="dropdown-menu">
								<button class="ajax-button" data-token="{{ csrf_token() }}" data-filter-type="date" data-filter-value="{{ \Carbon\Carbon::today() }}">Today</button>
								<button class="ajax-button" data-token="{{ csrf_token() }}" data-filter-type="date" data-filter-value="{{ \Carbon\Carbon::today()->subWeek() }}">Past Week</button>
								<button class="ajax-button" data-token="{{ csrf_token() }}" data-filter-type="date" data-filter-value="{{ \Carbon\Carbon::today()->subMonth() }}">Past Month</button>
								<button class="ajax-button" data-token="{{ csrf_token() }}" data-filter-type="date" data-filter-value="{{ \Carbon\Carbon::today()->subMonths(3) }}">Past 3 Months</button>
								<button class="ajax-button" data-token="{{ csrf_token() }}" data-filter-type="date" data-filter-value="{{ \Carbon\Carbon::today()->subMonths(6) }}">Past 6 Months</button>
								<button class="ajax-button" data-token="{{ csrf_token() }}" data-filter-type="date" data-filter-value="{{ \Carbon\Carbon::today()->subYear() }}">Past Year</button>
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
										<span style="color:#FD8099; font-weight: bold;">Tracking Number Deleted</span>
									@endif								
							</td>
							<td class="lead-row-caller">
								@if (!$lead->caller_name)
									<strong><em>No Caller ID</em>, @if (!$lead->city) Unknown @else {{ ucwords(strtolower($lead->city)) }} @endif</strong>
								@else
									<strong>{{ $lead->caller_name }}, {{ ucwords(strtolower($lead->city)) }}</strong>
								@endif
								<br>
								{{ $lead->caller_number }}
							</td>
							<td class="lead-row-status">
								@if (!$lead->status)
									<i class="fas fa-times" style="color: #FD8099; width: 16px;"></i>&nbsp;No Answer
								@else	
									<i class="fas fa-check" style="color: #26DAD2;"></i>&nbsp;<span style="text-transform: capitalize">{{ $lead->status }}</span>
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
