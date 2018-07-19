@extends('layouts.master')

@section('content')
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
@stop