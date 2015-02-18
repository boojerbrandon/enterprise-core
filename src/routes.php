<?php

// this should go away...
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

				// add route name if available
				if (isset($route['before'])) {
					$extra['before'] = $route['before'];
				}

				// create route
				Route::match($route['verbs'], $uri, $extra);
			}
		}
	}	
}