<?php namespace Activewebsite\EnterpriseCore\Facades;

use Illuminate\Support\Facades\Facade;


class SeoFacade extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'seo';
	}

}
