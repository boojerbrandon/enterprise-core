<?php namespace Activewebsite\EnterpriseCore;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

class EnterpriseCoreServiceProvider extends ServiceProvider {
	
	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// register package commands
		$this->registerPackageCommands();

		// register package facades
		$this->registerFacades();
		
		// merge configs so app inherits package
		$this->mergeConfigFrom(__DIR__.'/config/enterprise-core.php', 'enterpriseCore');
	}	

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		// tell app where views are held
		$this->loadViewsFrom(__DIR__.'/resources/views', 'enterpriseCore');

		// tell app where translations are held
		$this->loadTranslationsFrom(__DIR__.'/resources/lang', 'enterpriseCore');
			
		// include filters
		include __DIR__.'/filters.php';

		// include routes
		include __DIR__.'/routes.php';

		// define where the publisher should put things
		$this->publishes([
			__DIR__.'/config/enterprise-core.php' => config_path('enterprise-core.php'),
			__DIR__.'/resources/views' => base_path('resources/views/vendor/enterpriseCore'),
		]);
	}

	/**
	 * Register any package facades
	 * 
	 * @return void
	 */
	public function registerFacades()
	{
		App::bind('analytics', function() {
			return new \Activewebsite\EnterpriseCore\Models\Analytics\AnalyticCodes;
		});
		App::bind('seo', function() {
			return new \Activewebsite\EnterpriseCore\Models\Seo\Seo;
		});
	}

	/**
	 * gets the route definition file path
	 * @return string
	 */
	public function getRouteDefinitionsPath()
	{
		return __DIR__.'/routes/route-definitions.php';
	}
	
	/** 
	 * Register all package commands
	 * 
	 * @return void;
	 */
	public function registerPackageCommands()
	{
		$this->app['compileCoreRoutes'] = $this->app->share(function($app) {
            return new \Activewebsite\EnterpriseCore\Commands\RouteCompiler;
        });

        // add command to artisan
		$this->commands('compileCoreRoutes');
	}

}
