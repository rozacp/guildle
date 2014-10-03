<!DOCTYPE html>
<html class="no-js">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Guildle</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="/css/style.css">
</head>

<body>
	<nav>
		<ul>
			<li><a href="{{ route('home') }}">Home</a></li>
			<li><a href="{{ route('faq.index') }}">Faq</a></li>
			<li><a href="{{ route('contact') }}">Contact</a></li>
			<li><a href="{{ route('logout') }}">Logout</a></li>
			<li><a href="{{ URL::to('test') }}">Test</a></li>
		</ul>
	</nav>
	<div class="main">
		@yield('content')
	</div>
	<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> -->
	<!-- <script src="/js/main.js"></script> -->
	@yield('scripts')
</body>
</html>