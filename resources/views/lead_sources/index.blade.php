@extends('layouts.master')

@section('title')
	Manage My Tracking Numbers
@stop

@section('content')
<div class="table-section">
	<div class="row">
		<div class="col-10">
			<h1>My Tracking Numbers</h1>
		</div>
		<div class="col-2">
			<button class="nav-addNumber" data-toggle="modal" data-target="#addNumber">Add Number&nbsp;&nbsp;<i class="fas fa-plus-circle" style="color: #fff"></i></button>
		</div>
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