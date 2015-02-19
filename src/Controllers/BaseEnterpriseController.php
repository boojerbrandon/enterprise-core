<?php namespace Activewebsite\EnterpriseCore\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\View;

class BaseEnterpriseController extends BaseController {

	public $tpl_args = [];
	private $layout;

	public function __construct()
	{
		$this->layout = 'enterpriseCore::layout';
	}

	public function setLayout($view = '')
	{
		$this->layout = $view;
	}

	public function getLayout()
	{
		return $this->layout;
	}

	public function renderView($view = '', $args = array())
	{
		$final_args = array_merge($this->tpl_args, $args);
		$final_args['layout'] = $this->getLayout();

		return View::make($view, $final_args);

	}

}