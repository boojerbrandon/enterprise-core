<?php namespace Activewebsite\EnterpriseCore\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class AccountController extends BaseController {

	public function index()
	{
		$user = Sentinel::getUser();
		$persistence = Sentinel::getPersistenceRepository();
		return View::make('enterpriseCore::sentinel.account.home', compact('user', 'persistence'));
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