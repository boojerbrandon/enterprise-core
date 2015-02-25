<?php namespace Booj\EnterpriseCore\Commands;

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
	protected $description = 'Compiles all of the Booj package routes into a giant array.';

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
			if (strpos($provider, 'Booj') !== false) {
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

		\DB::statement("DROP TABLE IF EXISTS routes");
		\DB::statement("CREATE TABLE routes(
					   id int not null primary key auto_increment,
					   uri varchar(255),
					   verbs varchar(255),
					   params varchar(255),
					   name varchar(255),
					   controller varchar(255),
					   method varchar(255),
					   `where` varchar(255),
					   `before` varchar(255),
					   site_types varchar(255),
					   roles varchar(255))");

		foreach($routes as $route)
		{
			$verbs = implode(',',$route['verbs']);
			$types = implode(',',$route['siteTypes']);
			$roles = implode(',',$route['roles']);
			\DB::statement("INSERT INTO routes(uri,verbs,params,name,
										       controller,method,`where`,
										       `before`,site_types,roles) 
							VALUES(?,?,?,?,?,?,?,?,?,?)",
							array($route['uri'],$verbs,$route['params'],
								  $route['name'],$route['controller'],
								  $route['method'],$route['where'],
								  $route['before'],$types,$roles));
		}
		
		$this->info('Core Routes have been compiled.');
	}
}
