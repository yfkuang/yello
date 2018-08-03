@extends('layouts.master')

@section('title')
	Call Tracking by Leadlabs
@stop

@section('content')
	<div class="table-section">
		{{ Form::open(['route' => 'login', 'method' => 'POST', 'class' => 'login-form']) }}
			{!! Form::text('email', '', ['placeholder' => 'Email']) !!}
			{!! Form::password('password',['placeholder' => 'Password']) !!}
			{!! Form::hidden('tokenID') !!}
			{!! Form::button('Login', ['class' => 'btn btn-primary login']) !!}
		{{ Form::close() }}
	</div>
@stop