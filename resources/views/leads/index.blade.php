@extends('layouts.master')

@section('content')
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
		<h3>Leads</h3>
		<table>
			
		</table>
	</div>
@stop

@section('scripts')
    {!! Html::script('//cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js') !!}
    {!! Html::script('//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js')!!}
    {!! Html::script('/js/pieCharts.js') !!}
@stop
