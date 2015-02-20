<?php namespace Booj\EnterpriseCore\Controllers;

use Booj\EnterpriseCore\Controllers\BaseAuthorizedController;

class BaseAdminController extends BaseAuthorizedController {

	/**
	 * Constructor
	 * 
	 * This constructor ensures that any class that extends this will be protected
	 * in that there must be a logged in user to pass and they must have admin access
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		// before anything is run make sure there is a logged in user and has admin access
		$this->beforeFilter('auth.admin');

		// this controller uses the admin layout by default
		$this->setLayout('enterpriseCore::admin.layout');
	}
}