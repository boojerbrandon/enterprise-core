<?php namespace Activewebsite\EnterpriseCore\Controllers\UserManagement;

use Activewebsite\EnterpriseCore\Controllers\BaseEnterpriseController;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Activewebsite\EnterpriseCore\Facades\SeoFacade as SEO;

class AuthController extends BaseEnterpriseController {

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
	
	/**
	 * Show the form for logging the user in.
	 *
	 * @return \Illuminate\View\View
	 */
	public function login()
	{
		return $this->renderView('enterpriseCore::sentinel.login');
	}

	public function logout()
	{
		Sentinel::logout();
		return Redirect::to('/');
	}

	public function wait()
	{
		return $this->renderView('enterpriseCore::sentinel.wait');
	}

	public function reset()
	{
		return $this->renderView('enterpriseCore::sentinel.reset.begin');
	}

	public function processReset()
	{
		$rules = [
			'email' => 'required|email',
		];

		$validator = Validator::make(Input::get(), $rules);

		if ($validator->fails()) {
			return Redirect::back()
				->withInput()
				->withErrors($validator);
		}

		$email = Input::get('email');

		$user = Sentinel::findByCredentials(compact('email'));

		if ( ! $user) {
			return Redirect::back()
				->withInput()
				->withErrors('No user with that email address belongs in our system.');
		}

		$reminder = Reminder::exists($user) ?: Reminder::create($user);

		$code = $reminder->code;

		$sent = Mail::send('enterpriseCore::sentinel.emails.reminder', compact('user', 'code'), function($m) use ($user) {
			$m->to($user->email)->subject('Reset your account password.');
		});

		if ($sent === 0) {
			return Redirect::route('admin_register')
				->withErrors('Failed to send reset password email.');
		}

		return Redirect::route('admin_wait');
	}

	public function activateAccount($id, $code) {
		$user = Sentinel::findById($id);

		if ( ! Activation::complete($user, $code)) {
			return Redirect::route('admin_login')
				->withErrors('Invalid or expired activation code.');
		}

		return Redirect::route('admin_login')
			->withSuccess('Account activated.');
	}

	public function completeReset($id, $code)
	{
		$user = Sentinel::findById($id);
		return $this->renderView('enterpriseCore::sentinel.reset.complete');
	}

	public function processCompleteReset($id, $code) {
		$rules = [
			'password' => 'required|confirmed',
		];

		$validator = Validator::make(Input::get(), $rules);

		if ($validator->fails()) {
			return Redirect::back()
				->withInput()
				->withErrors($validator);
		}

		$user = Sentinel::findById($id);

		if ( ! $user) {
			return Redirect::back()
				->withInput()
				->withErrors('The user no longer exists.');
		}

		if ( ! Reminder::complete($user, $code, Input::get('password'))) {
			return Redirect::route('admin_login')
				->withErrors('Invalid or expired reset code.');
		}

		return Redirect::route('admin_login')
			->withSuccess("Password Reset.");
	}

	public function deactivate()
	{
		$user = Sentinel::check();

		Activation::remove($user);

		return Redirect::back()
			->withSuccess('Account deactivated.');
	}

	public function reactivate()
	{
		if ( ! $user = Sentinel::check()) {
			return Redirect::route('admin_login');
		}

		$activation = Activation::exists($user) ?: Activation::create($user);

		$code = $activation->code;

		$sent = Mail::send('enterpriseCore::sentinel.emails.activate', compact('user', 'code'), function($m) use ($user) {
			$m->to($user->email)->subject('Activate Your Account');
		});

		if ($sent === 0) {
			return Redirect::route('admin_register')
				->withErrors('Failed to send activation email.');
		}

		return Redirect::route('admin_account')
			->withSuccess('Account activated.');
	}

	/**
	 * Handle posting of the form for logging the user in.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function processLogin()
	{
		try
		{
			$input = Input::all();

			$rules = [
				'email'    => 'required|email',
				'password' => 'required',
			];

			$validator = Validator::make($input, $rules);

			if ($validator->fails())
			{
				return Redirect::back()
					->withInput()
					->withErrors($validator);
			}

			$remember = (bool) Input::get('remember', false);

			if (Sentinel::authenticate(Input::all(), $remember))
			{
				return Redirect::intended(route('admin_account'));
			}

			$errors = trans('enterpriseCore::sentinel.invalid_credentials_error_message');
		}
		catch (NotActivatedException $e)
		{
			$errors = trans('enterpriseCore::sentinel.account_not_activated_message');

			return Redirect::route('admin_reactivate')->with('user', $e->getUser());
		}
		catch (ThrottlingException $e)
		{
			$delay = $e->getDelay();

			$errors = trans('enterpriseCore::sentinel.account_blocked_message') . " {$delay} second(s).";
		}

		return Redirect::back()
			->withInput()
			->withErrors($errors);
	}

	/**
	 * Show the form for the user registration.
	 *
	 * @return \Illuminate\View\View
	 */
	public function register()
	{
		return $this->renderView('enterpriseCore::sentinel.register');
	}

	/**
	 * Handle posting of the form for the user registration.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function processRegistration()
	{
		$input = Input::all();

		$rules = [
			'email'            => 'required|email|unique:users',
			'password'         => 'required',
			'password_confirm' => 'required|same:password',
		];

		$validator = Validator::make($input, $rules);

		if ($validator->fails())
		{
			return Redirect::back()
				->withInput()
				->withErrors($validator);
		}

		if ($user = Sentinel::register($input))
		{
			$activation = Activation::create($user);

			$code = $activation->code;

			$sent = Mail::send('enterpriseCore::sentinel.emails.activate', compact('user', 'code'), function($m) use ($user)
			{
				$m->to($user->email)->subject(trans('enterpriseCore::sentinel.activation_subject'));
			});

			if ($sent === 0)
			{
				return Redirect::route('admin_register')
					->withErrors(trans('enterpriseCore::sentinel.activation_email_error_message'));
			}

			return Redirect::route('admin_login')
				->withSuccess(trans('enterpriseCore::sentinel.account_create_success_message'))
				->with('userId', $user->getUserId());
		}

		return Redirect::route('admin_register')
			->withInput()
			->withErrors(trans('enterpriseCore::sentinel.account_register_error_message'));
	}

}
