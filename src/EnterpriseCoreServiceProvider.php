<?php namespace Activewebsite\EnterpriseCore;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
		
		// add command to artisan
		$this->commands('compileCoreRoutes');

		// merge configs so app inherits package
		$this->mergeConfigFrom(__DIR__.'/config/enterprise-core.php', 'enterprisecore');
	}	

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		// tell app where views are held
		$this->loadViewsFrom(__DIR__.'/resources/views', 'enterprisecore');

		// tell app where translations are held
		$this->loadTranslationsFrom(__DIR__.'/resources/lang', 'enterprisecore');
			
		// include filters
		include __DIR__.'/filters.php';

		// include routes
		include __DIR__.'/routes.php';

		// register view composers
		$this->registerViewComposers();
	}

	/**
	 * Register all view composers
	 * 
	 * @return void
	 */
	
	public function registerViewComposers()
	{
		// when we make a quick search, build some stuff for it
		View::composer('components.quick-search', function($view){
			// we can access a passed in argument like this: $view['title'];
			$view['searchConfigs'] = [
				'price_range' => [100000,200000,300000,400000,500000,600000,700000,800000],
			];
		});
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
	}
}
