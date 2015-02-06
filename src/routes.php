<?php

if (Storage::exists('core-routes.php')) {
	$compiledRoutes = unserialize(Storage::get('core-routes.php'));
	if (!empty($compiledRoutes)) {
		foreach ($compiledRoutes as $route) {
			Route::match($route['verb'], $route['uri'], ['as' => $route['name'], 'uses' => $route['controller'] . '@' . $route['method']]);
		}
	}
}