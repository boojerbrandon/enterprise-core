<?php namespace Activewebsite\EnterpriseCore\Controllers;

use Illuminate\Support\Facades\View;

class AdminHomepageController extends AuthorizedController {

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Display a homepage.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return View::make('enterpriseCore::admin.homepage');
	}

}
