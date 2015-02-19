<?php namespace Activewebsite\EnterpriseCore\Controllers;

use Activewebsite\EnterpriseCore\Controllers\BaseEnterpriseController;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class AuthorizedController extends BaseEnterpriseController {

	/* current logged in user */
	protected $user;

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
		
		// get the current user
		$this->user = Sentinel::getUser();
	}

}
