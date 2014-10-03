@extends('default')

@section('content')

	<h1>Edit an existing FAQ</h1>

	{!! Form::model($faq, ['route' => ['faq.update', $faq->id], 'method' => 'put']) !!}

	{!! Form::label('title', 'Title') !!}
	{!! Form::text('title') !!}
	<br><br>
	{!! Form::label('body', 'Body') !!}
	{!! Form::textarea('body') !!}
	<br><br>
	{!! Form::submit('Enter FAQ') !!}

	{!! Form::close() !!}
@stop