<?php namespace Activewebsite\EnterpriseCore\Controllers\UserManagement;

use Activewebsite\EnterpriseCore\Controllers\AuthorizedController;
use Illuminate\Support\Facades\Redirect;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class AccountController extends AuthorizedController {

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		// this controller uses the admin layout by default
		$this->setLayout('enterpriseCore::admin.layout');
	}
	
	public function index()
	{
		$user = Sentinel::getUser();
		$persistence = Sentinel::getPersistenceRepository();
		return $this->renderView('enterpriseCore::sentinel.account.home', compact('user', 'persistence'));
	}

	public function killCurrentUserSession()
	{
		$user = Sentinel::getUser();
		Sentinel::getPersistenceRepository()->flush($user);
		return Redirect::back();
	}

	public function killAllCurrentUserSessions()
	{
		$user = Sentinel::getUser();
		Sentinel::getPersistenceRepository()->flush($user, false);
		return Redirect::back();
	}

	public function killSessionByCode($code)
	{
		Sentinel::getPersistenceRepository()->remove($code);
		return Redirect::back();
	}

}