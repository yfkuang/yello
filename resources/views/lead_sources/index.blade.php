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
				@include('lead_sources.leadsources')
			</tbody>
		</table>
	</div>
</div>
@stop