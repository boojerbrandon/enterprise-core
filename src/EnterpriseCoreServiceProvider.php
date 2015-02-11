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
	}	

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
	
		// load our routes
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
		$this->app['compileCoreRoutes'] = $this->app->share(function($app)
        {
            return new \Activewebsite\EnterpriseCore\CommandsRouteCompiler;
        });
	}
}
