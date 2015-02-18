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

Sentinel::disableCheckpoints();

Route::group(['prefix' => 'admin'], function() {
	
	// admin (need to be an administrator)
	Route::group(['before' => 'auth.admin'], function() {
		
		// manage roles
		Route::group(['prefix' => 'roles'], function() {
			Route::get('/', ['as' => 'admin_roles', 'uses' => 'Activewebsite\EnterpriseCore\Controllers\RolesController@index']);
			Route::get('create', ['as' => 'admin_create_roles', 'uses' => 'Activewebsite\EnterpriseCore\Controllers\RolesController@create']);
			Route::post('create', 'Activewebsite\EnterpriseCore\Controllers\RolesController@store');
			Route::get('{id}', ['as' => 'admin_edit_roles', 'uses' => 'Activewebsite\EnterpriseCore\Controllers\RolesController@edit']);
			Route::post('{id}', 'Activewebsite\EnterpriseCore\Controllers\RolesController@update');
			Route::get('{id}/delete', ['as' => 'admin_delete_roles', 'uses' => 'Activewebsite\EnterpriseCore\Controllers\RolesController@delete']);
		});

		// manage users
		Route::group(['prefix' => 'users'], function() {
			Route::get('/', ['as' => 'admin_users', 'uses' => 'Activewebsite\EnterpriseCore\Controllers\UsersController@index']);
			Route::get('create', ['as' => 'admin_create_users', 'uses' => 'Activewebsite\EnterpriseCore\Controllers\UsersController@create']);
			Route::post('create', 'Activewebsite\EnterpriseCore\Controllers\UsersController@store');
			Route::get('{id}', ['as' => 'admin_edit_users', 'uses' => 'Activewebsite\EnterpriseCore\Controllers\UsersController@edit']);
			Route::post('{id}', 'Activewebsite\EnterpriseCore\Controllers\UsersController@update');
			Route::get('{id}/delete', ['as' => 'admin_delete_users', 'uses' => 'Activewebsite\EnterpriseCore\Controllers\UsersController@delete']);
		});
	});

	// account (just need to be logged in)
	Route::group(['before' => 'auth', 'prefix' => 'account'], function() {
		Route::get('/', ['as' => 'admin_account', function() {
			$user = Sentinel::getUser();

			$persistence = Sentinel::getPersistenceRepository();

			return View::make('enterpriseCore::sentinel.account.home', compact('user', 'persistence'));
		}]);

		Route::get('kill', ['as' => 'admin_kill', function() {
			$user = Sentinel::getUser();

			Sentinel::getPersistenceRepository()->flush($user);

			return Redirect::back();
		}]);

		Route::get('kill-all', ['as' => 'admin_kill_all', function() {
			$user = Sentinel::getUser();

			Sentinel::getPersistenceRepository()->flush($user, false);

			return Redirect::back();
		}]);

		Route::get('kill/{code}', ['as' => 'admin_kill_session_key', function($code) {
			Sentinel::getPersistenceRepository()->remove($code);

			return Redirect::back();
		}]);
	});


	// dashboard
	Route::get('/', ['before' => 'auth', 'as', 'admin_dashboard', function() {
		return View::make('enterpriseCore::sentinel.dashboard');
	}]);


	// logout
	Route::get('logout', ['as' => 'admin_logout', function() {
		Sentinel::logout();
		return Redirect::to('/');
	}]);


	// login
	Route::get('login', ['as' => 'admin_login', 'uses' => 'Activewebsite\EnterpriseCore\Controllers\AuthController@login']);
	Route::post('login', 'Activewebsite\EnterpriseCore\Controllers\AuthController@processLogin');


	// register
	Route::get('register', ['as' => 'admin_register', 'uses' => 'Activewebsite\EnterpriseCore\Controllers\AuthController@register']);
	Route::post('register', 'Activewebsite\EnterpriseCore\Controllers\AuthController@processRegistration');


	// wait
	Route::get('wait', ['as' => 'admin_wait', function() {
		return View::make('enterpriseCore::sentinel.wait');
	}]);


	// reset
	Route::get('reset', ['as' => 'admin_reset_password', function() {
		return View::make('enterpriseCore::sentinel.reset.begin');
	}]);
	Route::post('reset', function() {
		$rules = [
			'email' => 'required|email',
		];

		$validator = Validator::make(Input::get(), $rules);

		if ($validator->fails()) {
			return Redirect::back()
				->withInput()
				->withErrors($validator);
		}

		$email = Input::get('email');

		$user = Sentinel::findByCredentials(compact('email'));

		if ( ! $user) {
			return Redirect::back()
				->withInput()
				->withErrors('No user with that email address belongs in our system.');
		}

		$reminder = Reminder::exists($user) ?: Reminder::create($user);

		$code = $reminder->code;

		$sent = Mail::send('enterpriseCore::sentinel.emails.reminder', compact('user', 'code'), function($m) use ($user) {
			$m->to($user->email)->subject('Reset your account password.');
		});

		if ($sent === 0) {
			return Redirect::route('admin_register')
				->withErrors('Failed to send reset password email.');
		}

		return Redirect::route('admin_wait');
	});


	// complete reset
	Route::get('reset/{id}/{code}', function($id, $code) {
		$user = Sentinel::findById($id);

		return View::make('enterpriseCore::sentinel.reset.complete');

	})->where('id', '\d+');
	Route::post('reset/{id}/{code}', function($id, $code) {
		$rules = [
			'password' => 'required|confirmed',
		];

		$validator = Validator::make(Input::get(), $rules);

		if ($validator->fails()) {
			return Redirect::back()
				->withInput()
				->withErrors($validator);
		}

		$user = Sentinel::findById($id);

		if ( ! $user) {
			return Redirect::back()
				->withInput()
				->withErrors('The user no longer exists.');
		}

		if ( ! Reminder::complete($user, $code, Input::get('password'))) {
			return Redirect::route('admin_login')
				->withErrors('Invalid or expired reset code.');
		}

		return Redirect::route('admin_login')
			->withSuccess("Password Reset.");
	})->where('id', '\d+');


	// activate account
	Route::get('activate/{id}/{code}', ['as' => 'admin_activate', function($id, $code) {
		$user = Sentinel::findById($id);

		if ( ! Activation::complete($user, $code)) {
			return Redirect::route('admin_login')
				->withErrors('Invalid or expired activation code.');
		}

		return Redirect::route('admin_login')
			->withSuccess('Account activated.');
	}])->where('id', '\d+');


	// reactivate current user
	Route::get('reactivate', ['as' => 'admin_reactivate', function() {
		if ( ! $user = Sentinel::check()) {
			return Redirect::route('admin_login');
		}

		$activation = Activation::exists($user) ?: Activation::create($user);

		$code = $activation->code;

		$sent = Mail::send('enterpriseCore::sentinel.emails.activate', compact('user', 'code'), function($m) use ($user) {
			$m->to($user->email)->subject('Activate Your Account');
		});

		if ($sent === 0) {
			return Redirect::route('admin_register')
				->withErrors('Failed to send activation email.');
		}

		return Redirect::route('admin_account')
			->withSuccess('Account activated.');
	}])->where('id', '\d+');


	// deactivate current account
	Route::get('deactivate', ['as' => 'admin_deactivate', function() {
		$user = Sentinel::check();

		Activation::remove($user);

		return Redirect::back()
			->withSuccess('Account deactivated.');
	}]);
});

