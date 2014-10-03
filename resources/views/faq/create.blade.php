@extends('default')

@section('content')

	<h1>Enter thy FAQ</h1>

	{!! Form::open(['route' => 'faq.store']) !!}

	{!! Form::label('title', 'Title') !!}
	{!! Form::text('title') !!}
	<br><br>
	{!! Form::label('body', 'Body') !!}
	{!! Form::textarea('body') !!}
	<br><br>
	{!! Form::submit('Enter FAQ') !!}

	{!! Form::close() !!}
@stop