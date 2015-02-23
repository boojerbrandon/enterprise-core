<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ SEO::getPageTitle() }}</title>
	<meta name="keywords" content="{{ SEO::getPageKeywords() }}">
	<meta name="description" content="{{ SEO::getPageDescription() }}">
	<link href="{{ asset('assets/dist/css/app.css') }}" rel="stylesheet">
	<script data-main="appMain" src="{{ asset('assets/dist/js/require.js') }}"></script>
</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Enterprise 2.0</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="/">Home</a></li>
				</ul>

				<ul class="nav navbar-nav navbar-right">
					@if ($current_user)
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ $current_user->first_name or null }} {{ $current_user->last_name or null }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ URL::route('admin_account') }}">Account</a></li>
								<li><a href="{{ URL::route('admin_logout') }}">Logout</a></li>
							</ul>
						</li>
					@else
						<li><a href="{{ URL::route('admin_login') }}">Login</a></li>
						<li><a href="{{ URL::route('admin_register') }}">Register</a></li>
					@endif
				</ul>
			</div>
		</div>
	</nav>

	<div class="container">
		<div class="col-sm-3">
			{!! RenderComponent::render('QuickSearchComponent', ['title' => 'foo title']) !!}
			
		</div>
		<div class="col-sm-9">
			@yield('content')
		</div>
	</div>

	<!-- Scripts -->
	@yield('scripts')
	
	{!! Analytics::getGACode() !!}
</body>
</html>
