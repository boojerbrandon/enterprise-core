<?php namespace Activewebsite\EnterpriseCore;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\App as App;
use Illuminate\Support\Facades\Storage as Storage;

class RouteCompiler extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'compileCoreRoutes';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Compiles all of the Activewebsite package routes into a giant array.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$providers = Config::get('app.providers');
		$appInstance = App();
		$files = [];
		foreach ($providers as $provider) {
			if (strpos($provider, 'Activewebsite') !== false) {
				$providerInstance = $appInstance->make($provider, [$appInstance]);
				if (method_exists($providerInstance, 'getRouteDefinitionsPath')) {
					$files[] = $providerInstance->getRouteDefinitionsPath();
				}
			}
		}

		$routes = [];
		if (!empty($files)) {
			foreach ($files as $loc) {
				if (file_exists($loc)) {
					$str = include($loc);
					$routes = array_merge($routes, $str);
				}
			}
		}

		Storage::put('core-routes.php', serialize($routes));
		
		$this->info('Core Routes have been compiled.');
	}
}
