<?php

if (Storage::exists('core-routes.php')) {
	$compiledRoutes = unserialize(Storage::get('core-routes.php'));
	if (!empty($compiledRoutes)) {
		foreach ($compiledRoutes as $route) {
			// we need at least these things to make a route verbs, controller, method
			if (isset($route['verbs']) && isset($route['controller']) && isset($route['method'])) {
			
				// build uri
				$uri = $route['uri'];
				if (isset($route['params'])) {
					$uri .= $route['params'];
				}

				$extra = [
					'uses' => $route['controller'] . '@' . $route['method']
				];

				// add route name if available
				if (isset($route['name'])) {
					$extra['as'] = $route['name'];
				}

				// create route
				Route::match($route['verbs'], $uri, $extra);
			}
		}
	}	
}

// Disable checkpoints (throttling, activation) for demo purposes
Sentinel::disableCheckpoints();

Route::get('logout', function()
{
	Sentinel::logout();

	return Redirect::to('/');
});


// manage roles
Route::group(['before' => 'auth.admin', 'prefix' => 'roles'], function()
{
	Route::get('/', 'Activewebsite\EnterpriseCore\Controllers\RolesController@index');
	Route::get('create', 'Activewebsite\EnterpriseCore\Controllers\RolesController@create');
	Route::post('create', 'Activewebsite\EnterpriseCore\Controllers\RolesController@store');
	Route::get('{id}', 'Activewebsite\EnterpriseCore\Controllers\RolesController@edit');
	Route::post('{id}', 'Activewebsite\EnterpriseCore\Controllers\RolesController@update');
	Route::get('{id}/delete', 'Activewebsite\EnterpriseCore\Controllers\RolesController@delete');
});

// manage users
Route::group(['before' => 'auth.admin', 'prefix' => 'users'], function()
{
	Route::get('/', 'Activewebsite\EnterpriseCore\Controllers\UsersController@index');
	Route::get('create', 'Activewebsite\EnterpriseCore\Controllers\UsersController@create');
	Route::post('create', 'Activewebsite\EnterpriseCore\Controllers\UsersController@store');
	Route::get('{id}', 'Activewebsite\EnterpriseCore\Controllers\UsersController@edit');
	Route::post('{id}', 'Activewebsite\EnterpriseCore\Controllers\UsersController@update');
	Route::get('{id}/delete', 'Activewebsite\EnterpriseCore\Controllers\UsersController@delete');
});

// login
Route::get('login', 'Activewebsite\EnterpriseCore\Controllers\AuthController@login');
Route::post('login', 'Activewebsite\EnterpriseCore\Controllers\AuthController@processLogin');

// register
Route::get('register', 'Activewebsite\EnterpriseCore\Controllers\AuthController@register');
Route::post('register', 'Activewebsite\EnterpriseCore\Controllers\AuthController@processRegistration');

// wait
Route::get('wait', function()
{
	return View::make('enterprisecore::sentinel.wait');
});

// activate account
Route::get('activate/{id}/{code}', function($id, $code)
{
	$user = Sentinel::findById($id);

	if ( ! Activation::complete($user, $code))
	{
		return Redirect::to("login")
			->withErrors('Invalid or expired activation code.');
	}

	return Redirect::to('login')
		->withSuccess('Account activated.');
})->where('id', '\d+');

// reactivate
Route::get('reactivate', function()
{
	if ( ! $user = Sentinel::check())
	{
		return Redirect::to('login');
	}

	$activation = Activation::exists($user) ?: Activation::create($user);

	// This is used for the demo, usually you would want
	// to activate the account through the link you
	// receive in the activation email
	Activation::complete($user, $activation->code);

	// $code = $activation->code;

	// $sent = Mail::send('sentinel.emails.activate', compact('user', 'code'), function($m) use ($user)
	// {
	// 	$m->to($user->email)->subject('Activate Your Account');
	// });

	// if ($sent === 0)
	// {
	// 	return Redirect::to('register')
	// 		->withErrors('Failed to send activation email.');
	// }

	return Redirect::to('account')
		->withSuccess('Account activated.');
})->where('id', '\d+');

Route::get('deactivate', function()
{
	$user = Sentinel::check();

	Activation::remove($user);

	return Redirect::back()
		->withSuccess('Account deactivated.');
});

Route::get('reset', function()
{
	return View::make('enterprisecore::sentinel.reset.begin');
});

Route::post('reset', function()
{
	$rules = [
		'email' => 'required|email',
	];

	$validator = Validator::make(Input::get(), $rules);

	if ($validator->fails())
	{
		return Redirect::back()
			->withInput()
			->withErrors($validator);
	}

	$email = Input::get('email');

	$user = Sentinel::findByCredentials(compact('email'));

	if ( ! $user)
	{
		return Redirect::back()
			->withInput()
			->withErrors('No user with that email address belongs in our system.');
	}

	// $reminder = Reminder::exists($user) ?: Reminder::create($user);

	// $code = $reminder->code;

	// $sent = Mail::send('enterprisecore::sentinel.emails.reminder', compact('user', 'code'), function($m) use ($user)
	// {
	// 	$m->to($user->email)->subject('Reset your account password.');
	// });

	// if ($sent === 0)
	// {
	// 	return Redirect::to('register')
	// 		->withErrors('Failed to send reset password email.');
	// }

	return Redirect::to('wait');
});

Route::get('reset/{id}/{code}', function($id, $code)
{
	$user = Sentinel::findById($id);

	return View::make('enterprisecore::sentinel.reset.complete');

})->where('id', '\d+');

Route::post('reset/{id}/{code}', function($id, $code)
{
	$rules = [
		'password' => 'required|confirmed',
	];

	$validator = Validator::make(Input::get(), $rules);

	if ($validator->fails())
	{
		return Redirect::back()
			->withInput()
			->withErrors($validator);
	}

	$user = Sentinel::findById($id);

	if ( ! $user)
	{
		return Redirect::back()
			->withInput()
			->withErrors('The user no longer exists.');
	}

	if ( ! Reminder::complete($user, $code, Input::get('password')))
	{
		return Redirect::to('login')
			->withErrors('Invalid or expired reset code.');
	}

	return Redirect::to('login')
		->withSuccess("Password Reset.");
})->where('id', '\d+');

Route::group(['prefix' => 'account', 'before' => 'auth'], function()
{

	Route::get('/', function()
	{
		$user = Sentinel::getUser();

		$persistence = Sentinel::getPersistenceRepository();

		return View::make('enterprisecore::sentinel.account.home', compact('user', 'persistence'));
	});

	Route::get('kill', function()
	{
		$user = Sentinel::getUser();

		Sentinel::getPersistenceRepository()->flush($user);

		return Redirect::back();
	});

	Route::get('kill-all', function()
	{
		$user = Sentinel::getUser();

		Sentinel::getPersistenceRepository()->flush($user, false);

		return Redirect::back();
	});

	Route::get('kill/{code}', function($code)
	{
		Sentinel::getPersistenceRepository()->remove($code);

		return Redirect::back();
	});

});
