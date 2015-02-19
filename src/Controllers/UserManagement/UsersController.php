<?php namespace Activewebsite\EnterpriseCore\Controllers\UserManagement;

use Activewebsite\EnterpriseCore\Controllers\BaseAdminController;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Cartalyst\Sentinel\Laravel\Facades\Activation;

class UsersController extends BaseAdminController {

	/**
	 * Holds the Sentinel Users repository.
	 *
	 * @var \Cartalyst\Sentinel\Users\EloquentUser
	 */
	protected $users;

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->users = Sentinel::getUserRepository();
	}

	/**
	 * Display a listing of users.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		$users = $this->users->createModel()->paginate();
	
		return $this->renderView('enterpriseCore::sentinel.users.index', compact('users'));
	}

	/**
	 * Show the form for creating new user.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		$user = $this->users->createModel();
		
		return $this->renderView('enterpriseCore::sentinel.users.form', compact('user'));
	}

	/**
	 * Handle posting of the form for creating new user.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating user.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		if ( ! $user = $this->users->createModel()->find($id)) {
			return Redirect::route('admin_users');
		}

		$all_roles = Sentinel::getRoleRepository()->createModel()->get();
	
		return $this->renderView('enterpriseCore::sentinel.users.edit_form', compact('user', 'all_roles'));
	}

	/**
	 * Handle posting of the form for updating user.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified user.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		if ($user = $this->users->createModel()->find($id))
		{
			$user->delete();

			return Redirect::route('admin_users');
		}

		return Redirect::route('admin_users');
	}

	/**
	 * Processes the form.
	 *
	 * @param  string  $mode
	 * @param  int     $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function processForm($mode, $id = null)
	{
		$input = array_filter(Input::all());

		$rules = [
			'first_name' => 'required',
			'last_name'  => 'required',
			'email'      => 'required|unique:users'
		];

		if ($id) {
			$user = $this->users->createModel()->find($id);

			$rules['email'] .= ",email,{$user->email},email";

			$messages = $this->validateUser($input, $rules);

			if ($messages->isEmpty()) {
				// get all roles
				$all_roles = Sentinel::getRoleRepository()->createModel()->get();
				
				// iterate over roles and attach/detach them
				foreach ($all_roles as $role) {
					$inst = Sentinel::findRoleById($role->id);
					
					if (in_array($role->id, $input['roles'])) {
						if (!$user->inRole($role->id)) {
							$inst->users()->attach($user);
						}
					} else {
						$inst->users()->detach($user);
					}
				}

				$this->users->update($user, $input);
			}
		} else {
			$messages = $this->validateUser($input, $rules);

			if ($messages->isEmpty()) {
				$user = $this->users->create($input);

				$code = Activation::create($user);

				Activation::complete($user, $code);
			}
		}

		if ($messages->isEmpty()) {
			return Redirect::route('admin_users');
		}

		return Redirect::back()->withInput()->withErrors($messages);
	}

	/**
	 * Validates a user.
	 *
	 * @param  array  $data
	 * @param  mixed  $id
	 * @return \Illuminate\Support\MessageBag
	 */
	protected function validateUser($data, $rules)
	{
		$validator = Validator::make($data, $rules);

		$validator->passes();

		return $validator->errors();
	}

}
