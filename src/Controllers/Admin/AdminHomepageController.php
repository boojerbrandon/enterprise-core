<?php namespace Booj\EnterpriseCore\Controllers\Admin;

use Booj\EnterpriseCore\Controllers\BaseAdminController;

class AdminHomepageController extends BaseAdminController {

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
		return $this->renderView('enterpriseCore::admin.homepage');
	}

}
