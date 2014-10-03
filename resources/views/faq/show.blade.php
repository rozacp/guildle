@extends('default')

@section('content')
	<h1>The single FAQ</h1>
	<hr>

	<h2>{{ $faq->title }}</h2>
	{!! $faq->body !!}
	<br><br>
	<a href="{{ route('faq.index') }}">Back to all FAQ</a>
	<a href="{{ route('faq.edit', [$faq->id]) }}">Edit FAQ</a>
	{!! Form::open(['route' => ['faq.destroy', $faq->id], 'method' => 'delete']) !!}
	    <button type="submit" >Delete FAQ</button>
	{!! Form::close() !!}
@stop