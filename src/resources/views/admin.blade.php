<!DOCTYPE html>
<html>
<head>
	<title>Sentinel</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
	</head>
	<body>

	<div class="flux clearfix">
		<div class="flux--1"></div>
		<div class="flux--2"></div>
		<div class="flux--3"></div>
		<div class="flux--4"></div>
		<div class="flux--5"></div>
	</div>

	<div class="container">

	  <nav class="navbar xnavbar-fixed-top navbar-inverse" role="navigation">

		<div class="navbar-header">
		  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		  </button>
		  <a class="navbar-brand" href="{{ URL::to('/') }}">Sentinel</a>
		</div>

		<div class="collapse navbar-collapse navbar-ex1-collapse">

		  <ul class="nav navbar-nav">
			<li{{ Request::is('/') ? ' class="active"' : null }}><a href="{{ URL::to('/') }}">Home</a></li>
			@if ( ! Sentinel::check())
			<li{{ Request::is('login') ? ' class="active"' : null }}><a href="{{ URL::to('login') }}">Login</a></li>
			<li{{ Request::is('register') ? ' class="active"' : null }}><a href="{{ URL::to('register') }}">Register</a></li>
			@elseif (Sentinel::hasAccess('admin'))
			<li{{ Request::is('users*') ? ' class="active"' : null }}><a href="{{ URL::to('users') }}">Users</a></li>
			<li{{ Request::is('roles*') ? ' class="active"' : null }}><a href="{{ URL::to('roles') }}">Roles</a></li>
			@endif
		  </ul>
		  <ul class="nav navbar-nav pull-right">
			<li><a href="https://cartalyst.com/manual/sentinel">Manual</a></li>
		  @if ($user = Sentinel::check())
			<li{{ Request::is('account') ? ' class="active"' : null }}><a href="{{ URL::to('account') }}">Account
			@if ( ! Activation::completed($user))
			<span class="label label-danger">Inactive</span>
			@endif
		  </a></li>
		  <li><a href="{{ URL::to('logout') }}">Logout</a></li>
		  @endif
		  </ul>

	  </div>
	</nav>

	@if ($errors->any())
	<div class="alert alert-danger alert-block">
	  <button type="button" class="close" data-dismiss="alert"><i class="fa fa-minus-square"></i></button>
	  <strong>Error</strong>
	  @if ($message = $errors->first(0, ':message'))
	  {{ $message }}
	  @else
	  Please check the form below for errors
	  @endif
	</div>
	@endif

	@if ($message = Session::get('success'))
	<div class="alert alert-success alert-block">
	  <button type="button" class="close" data-dismiss="alert"><i class="fa fa-minus-square"></i></button>
	  <strong>Success</strong> {{ $message }}
	</div>
	@endif


	@yield('body')
  </div>

	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

	<script>
		$('.tip').tooltip();
	</script>

	@yield('scripts')
</body>
</html>