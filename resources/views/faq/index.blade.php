@extends('default')

@section('content')
	<h1>The list of all FAQ</h1>
	<hr>


	@if ($faqs->count())

	@foreach ($faqs as $faq)
		<div class="faq">
			<h2><a href="{{ route('faq.show', [$faq->id]) }}">{{ $faq->title }}</a></h2>
			{!! Illuminate\Support\Str::words($faq->body, 10, ' ...') !!}
		</div>
	@endforeach

	@else

	<p>There are no frequently asked questions.</p>

	@endif

	<br><br>

	<a href="{{ route('faq.create') }}">Add new FAQ</a>
@stop
