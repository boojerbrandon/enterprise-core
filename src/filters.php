<?php

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

// temp for now
Sentinel::disableCheckpoints();


Route::filter('auth', function()
{
	if (! Sentinel::check())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::guest(route('admin_login'));
		}
	}
});

Route::filter('auth.admin', function()
{
	if (Sentinel::check() && ! Sentinel::hasAccess('admin'))
	{
		return Redirect::route('admin_account')->withErrors(['Only admins can access this page.']);
	}
});

Route::filter('auth.basic', function()
{
	return Sentinel::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (!Sentinel::check()) return Redirect::to('/');
});