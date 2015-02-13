<?php namespace Activewebsite\EnterpriseCore\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class AuthorizedController extends BaseController {

	public function __construct()
	{
		$this->beforeFilter('auth');

		$this->user = Sentinel::getUser();
	}

}
