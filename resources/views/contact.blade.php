@extends('default')

@section('content')
	<h1>Contact form</h1>
	<hr>
	{!! Form::open(['route' => 'contact']) !!}

	{!! Form::label('title', 'Title') !!}
	{!! Form::text('title') !!}
	<br><br>
	{!! Form::label('body', 'Body') !!}
	{!! Form::textarea('body') !!}
	<br><br>
	{!! Form::submit('Send away!') !!}

	{!! Form::close() !!}

@stop
