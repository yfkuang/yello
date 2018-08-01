@extends('layouts.master')

@section('title')
	Call Tracking by Leadlabs
@stop

@section('content')
	<div class="table-section">
		{{ Form::open(['route' => 'dashboard', 'method' => 'POST']) }}
			{!! Form::text('email', '', ['placeholder' => 'Email']) !!}
			{!! Form::password('password',['placeholder' => 'Password']) !!}
			{!! Form::submit('Login', ['class' => 'btn btn-primary']) !!}
		{{ Form::close() }}
	</div>
@stop