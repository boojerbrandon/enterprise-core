<?php namespace Booj\EnterpriseCore\Controllers;

use Booj\EnterpriseCore\Controllers\BaseEnterpriseController;


class BaseAuthorizedController extends BaseEnterpriseController {

	/**
	 * Constructor
	 * 
	 * This constructor ensures that any class that extends this will be protected
	 * in that there must be a logged in user to pass
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		
		// before anything is run make sure there is a logged in user
		$this->beforeFilter('auth');
	}

}
