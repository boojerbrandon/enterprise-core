<!DOCTYPE html>
<html>
	<head>
		<title>{{ SEO::getPageTitle() }}</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="{{ asset('assets/dist/css/admin.css') }}" rel="stylesheet">
		<script data-main="adminMain" src="{{ asset('assets/dist/js/require.js') }}"></script>
	</head>
	<body>
		<nav class="navbar navbar-default">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#admin-navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="{{ URL::to('/') }}">Dashboard</a>
				</div>
				<div class="collapse navbar-collapse" id="admin-navbar-collapse">
					<ul class="nav navbar-nav">
						<li{!! Request::is('/') ? ' class="active"' : null !!}><a href="{{ URL::to('/') }}">Home</a></li>
						@if ($current_user)
							<li{!! Request::is('admin/broker-dashboard') ? ' class="active"' : null !!}><a href="{{ URL::route('broker_dashboard') }}">Broker Dashboard</a></li>
							<li{!! Request::is('admin/users*') ? ' class="active"' : null !!}><a href="{{ URL::route('admin_users') }}">Users</a></li>
							<li{!! Request::is('admin/roles*') ? ' class="active"' : null !!}><a href="{{ URL::route('admin_roles') }}">Roles</a></li>
						@endif
					</ul>
					<ul class="nav navbar-nav pull-right">
						@if ($current_user)
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ $current_user->first_name or null }} {{ $current_user->last_name or null }} <span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									<li><a href="{{ URL::route('admin_account') }}">Account</a></li>
									<li><a href="{{ URL::route('admin_logout') }}">Logout</a></li>
								</ul>
							</li>
						@else
							<li{!! Request::is('admin/login') ? ' class="active"' : null !!}><a href="{{ URL::route('admin_login') }}">Login</a></li>
							<li{!! Request::is('admin/register') ? ' class="active"' : null !!}><a href="{{ URL::route('admin_register') }}">Register</a></li>
						@endif
					</ul>
				</div>
			</div>
		</nav>
		<div class="container">
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

			@yield('content')
		</div>

		<!-- scripts -->
		@yield('scripts')

		{!! Analytics::getGACode() !!}
	</body>
</html>
