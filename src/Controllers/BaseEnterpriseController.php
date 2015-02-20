<?php namespace Booj\EnterpriseCore\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\View;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class BaseEnterpriseController extends BaseController {

	public $tpl_args = [];
	private $layout;
	private $current_user;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		// set default layout
		$this->setLayout('enterpriseCore::layout');
		
		// set current user
		$this->setCurrentUser(Sentinel::getUser());
	}

	/**
	 * Set the current user
	 * 
	 * @param EloquentUser $user
	 * @return  void
	 */
	public function setCurrentUser($user = null)
	{
		$this->current_user = $user;
	}

	/**
	 * Get the current logged in user
	 * 
	 * @return null | EloquentUser
	 */
	public function getCurrentUser()
	{
		return $this->current_user;
	}

	/**
	 * Set the layout file
	 * 
	 * @param string $view
	 * @return void
	 */
	public function setLayout($view = '')
	{
		$this->layout = $view;
	}

	/**
	 * Get the layout file
	 * 
	 * @return string
	 */
	public function getLayout()
	{
		return $this->layout;
	}

	/**
	 * Render a blade template
	 * 
	 * @param  string $view The view name
	 * @param  array  $args The view arguments
	 * @return \Illuminate\View\View
	 */
	public function renderView($view = '', $args = array())
	{
		$final_args = array_merge($this->tpl_args, $args);
		$final_args['layout'] = $this->getLayout();
		$final_args['current_user'] = $this->getCurrentUser();

		return View::make($view, $final_args);
	}

}