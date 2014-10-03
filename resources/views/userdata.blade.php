@extends('default')

@section('content')
	<h1>User data form</h1>
	<hr>
	{!! Form::open(['route' => 'userdata']) !!}

	{!! Form::label('title', 'Name') !!}
	{!! Form::text('name') !!}
	<br><br>
	{!! Form::label('title', 'Age') !!}
	{!! Form::text('age') !!}
	<br><br>
	{!! Form::label('title', 'Gender') !!}
	{!! Form::text('gender') !!}
	<br><br>
	{!! Form::label('title', 'Email') !!}
	{!! Form::text('email') !!}
	<br><br>
	{!! Form::submit('Send away!') !!}

	{!! Form::close() !!}

@stop