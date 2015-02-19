<?php 

return [
	// account pages
	[
		'uri' => '/admin/account',
		'verbs' => ['get'],
		'params' => '',
		'name' => 'admin_account',
		'controller' => 'Activewebsite\EnterpriseCore\Controllers\UserManagement\AccountController', 
		'method' => 'index',
		'where' => '',
		'before' => 'auth.admin',
		'siteTypes' => ['company'],
		'roles' => [],
	],
	[
		'uri' => '/admin/account/kill',
		'verbs' => ['get'],
		'params' => '',
		'name' => 'admin_kill',
		'controller' => 'Activewebsite\EnterpriseCore\Controllers\UserManagement\AccountController', 
		'method' => 'killCurrentUserSession',
		'where' => '',
		'before' => 'auth',
		'siteTypes' => ['company'],
		'roles' => [],
	],
	[
		'uri' => '/admin/account/kill',
		'verbs' => ['get'],
		'params' => '/{code}',
		'name' => 'admin_kill_session_key',
		'controller' => 'Activewebsite\EnterpriseCore\Controllers\UserManagement\AccountController', 
		'method' => 'killSessionByCode',
		'where' => '',
		'before' => 'auth',
		'siteTypes' => ['company'],
		'roles' => [],
	],
	[
		'uri' => '/admin/account/kill-all',
		'verbs' => ['get'],
		'params' => '',
		'name' => 'admin_kill_all',
		'controller' => 'Activewebsite\EnterpriseCore\Controllers\UserManagement\AccountController', 
		'method' => 'killAllCurrentUserSessions',
		'where' => '',
		'before' => 'auth',
		'siteTypes' => ['company'],
		'roles' => [],
	],


	// roles
	[
		'uri' => '/admin/roles',
		'verbs' => ['get'],
		'params' => '',
		'name' => 'admin_roles',
		'controller' => 'Activewebsite\EnterpriseCore\Controllers\UserManagement\RolesController', 
		'method' => 'index',
		'where' => '',
		'before' => 'auth.admin',
		'siteTypes' => ['company'],
		'roles' => ['Administrator'],
	],
	[
		'uri' => '/admin/roles/create',
		'verbs' => ['get'],
		'params' => '',
		'name' => 'admin_create_roles',
		'controller' => 'Activewebsite\EnterpriseCore\Controllers\UserManagement\RolesController', 
		'method' => 'create',
		'where' => '',
		'before' => 'auth.admin',
		'siteTypes' => ['company'],
		'roles' => ['Administrator'],
	],
	[
		'uri' => '/admin/roles/create',
		'verbs' => ['post'],
		'params' => '',
		'name' => '',
		'controller' => 'Activewebsite\EnterpriseCore\Controllers\UserManagement\RolesController', 
		'method' => 'store',
		'where' => '',
		'before' => 'auth.admin',
		'siteTypes' => ['company'],
		'roles' => ['Administrator'],
	],	
	[
		'uri' => '/admin/roles',
		'verbs' => ['get'],
		'params' => '/{id}',
		'name' => 'admin_edit_roles',
		'controller' => 'Activewebsite\EnterpriseCore\Controllers\UserManagement\RolesController', 
		'method' => 'edit',
		'where' => '',
		'before' => 'auth.admin',
		'siteTypes' => ['company'],
		'roles' => ['Administrator'],
	],	
	[
		'uri' => '/admin/roles',
		'verbs' => ['post'],
		'params' => '/{id}',
		'name' => '',
		'controller' => 'Activewebsite\EnterpriseCore\Controllers\UserManagement\RolesController', 
		'method' => 'update',
		'where' => '',
		'before' => 'auth.admin',
		'siteTypes' => ['company'],
		'roles' => ['Administrator'],
	],	
	[
		'uri' => '/admin/roles',
		'verbs' => ['get'],
		'params' => '/{id}/delete',
		'name' => 'admin_delete_roles',
		'controller' => 'Activewebsite\EnterpriseCore\Controllers\UserManagement\RolesController', 
		'method' => 'delete',
		'where' => '',
		'before' => 'auth.admin',
		'siteTypes' => ['company'],
		'roles' => ['Administrator'],
	],	

	// users
	[
		'uri' => '/admin/users',
		'verbs' => ['get'],
		'params' => '',
		'name' => 'admin_users',
		'controller' => 'Activewebsite\EnterpriseCore\Controllers\UserManagement\UsersController', 
		'method' => 'index',
		'where' => '',
		'before' => 'auth.admin',
		'siteTypes' => ['company'],
		'roles' => ['Administrator'],
	],
	[
		'uri' => '/admin/users/create',
		'verbs' => ['get'],
		'params' => '',
		'name' => 'admin_create_users',
		'controller' => 'Activewebsite\EnterpriseCore\Controllers\UserManagement\UsersController', 
		'method' => 'create',
		'where' => '',
		'before' => 'auth.admin',
		'siteTypes' => ['company'],
		'roles' => ['Administrator'],
	],
	[
		'uri' => '/admin/users/create',
		'verbs' => ['post'],
		'params' => '',
		'name' => '',
		'controller' => 'Activewebsite\EnterpriseCore\Controllers\UserManagement\UsersController', 
		'method' => 'store',
		'where' => '',
		'before' => 'auth.admin',
		'siteTypes' => ['company'],
		'roles' => ['Administrator'],
	],	
	[
		'uri' => '/admin/users',
		'verbs' => ['get'],
		'params' => '/{id}',
		'name' => 'admin_edit_users',
		'controller' => 'Activewebsite\EnterpriseCore\Controllers\UserManagement\UsersController', 
		'method' => 'edit',
		'where' => '',
		'before' => 'auth.admin',
		'siteTypes' => ['company'],
		'roles' => ['Administrator'],
	],	
	[
		'uri' => '/admin/users',
		'verbs' => ['post'],
		'params' => '/{id}',
		'name' => '',
		'controller' => 'Activewebsite\EnterpriseCore\Controllers\UserManagement\UsersController', 
		'method' => 'update',
		'where' => '',
		'before' => 'auth.admin',
		'siteTypes' => ['company'],
		'roles' => ['Administrator'],
	],	
	[
		'uri' => '/admin/users',
		'verbs' => ['get'],
		'params' => '/{id}/delete',
		'name' => 'admin_delete_users',
		'controller' => 'Activewebsite\EnterpriseCore\Controllers\UserManagement\UsersController', 
		'method' => 'delete',
		'where' => '',
		'before' => 'auth.admin',
		'siteTypes' => ['company'],
		'roles' => ['Administrator'],
	],	

	// admin homepage
	[
		'uri' => '/admin',
		'verbs' => ['get'],
		'params' => '',
		'name' => 'admin_homepage',
		'controller' => 'Activewebsite\EnterpriseCore\Controllers\Admin\AdminHomepageController', 
		'method' => 'index',
		'where' => '',
		'before' => 'auth.admin',
		'siteTypes' => ['company'],
		'roles' => [],
	],

	// admin logout
	[
		'uri' => '/admin/logout',
		'verbs' => ['get'],
		'params' => '',
		'name' => 'admin_logout',
		'controller' => 'Activewebsite\EnterpriseCore\Controllers\UserManagement\AuthController', 
		'method' => 'logout',
		'where' => '',
		'before' => '',
		'siteTypes' => ['company'],
		'roles' => [],
	],

	// admin login
	[
		'uri' => '/admin/login',
		'verbs' => ['get'],
		'params' => '',
		'name' => 'admin_login',
		'controller' => 'Activewebsite\EnterpriseCore\Controllers\UserManagement\AuthController', 
		'method' => 'login',
		'where' => '',
		'before' => '',
		'siteTypes' => ['company'],
		'roles' => [],
	],
	[
		'uri' => '/admin/login',
		'verbs' => ['post'],
		'params' => '',
		'name' => '',
		'controller' => 'Activewebsite\EnterpriseCore\Controllers\UserManagement\AuthController', 
		'method' => 'processLogin',
		'where' => '',
		'before' => '',
		'siteTypes' => ['company'],
		'roles' => [],
	],

	// admin register
	[
		'uri' => '/admin/register',
		'verbs' => ['get'],
		'params' => '',
		'name' => 'admin_register',
		'controller' => 'Activewebsite\EnterpriseCore\Controllers\UserManagement\AuthController', 
		'method' => 'register',
		'where' => '',
		'before' => '',
		'siteTypes' => ['company'],
		'roles' => [],
	],
	[
		'uri' => '/admin/register',
		'verbs' => ['post'],
		'params' => '',
		'name' => '',
		'controller' => 'Activewebsite\EnterpriseCore\Controllers\UserManagement\AuthController', 
		'method' => 'processRegistration',
		'where' => '',
		'before' => '',
		'siteTypes' => ['company'],
		'roles' => [],
	],

	// deactivate current account
	[
		'uri' => '/admin/deactivate',
		'verbs' => ['get'],
		'params' => '',
		'name' => 'admin_deactivate',
		'controller' => 'Activewebsite\EnterpriseCore\Controllers\UserManagement\AuthController', 
		'method' => 'deactivate',
		'where' => '',
		'before' => '',
		'siteTypes' => ['company'],
		'roles' => [],
	],

	// reactivate current user
	[
		'uri' => '/admin/reactivate',
		'verbs' => ['get'],
		'params' => '',
		'name' => 'admin_reactivate',
		'controller' => 'Activewebsite\EnterpriseCore\Controllers\UserManagement\AuthController', 
		'method' => 'reactivate',
		'where' => '',
		'before' => '',
		'siteTypes' => ['company'],
		'roles' => [],
	],

	// wait
	[
		'uri' => '/admin/wait',
		'verbs' => ['get'],
		'params' => '',
		'name' => 'admin_wait',
		'controller' => 'Activewebsite\EnterpriseCore\Controllers\UserManagement\AuthController', 
		'method' => 'wait',
		'where' => '',
		'before' => '',
		'siteTypes' => ['company'],
		'roles' => [],
	],

	// reset
	[
		'uri' => '/admin/reset',
		'verbs' => ['get'],
		'params' => '',
		'name' => 'admin_reset_password',
		'controller' => 'Activewebsite\EnterpriseCore\Controllers\UserManagement\AuthController', 
		'method' => 'reset',
		'where' => '',
		'before' => '',
		'siteTypes' => ['company'],
		'roles' => [],
	],
	[
		'uri' => '/admin/reset',
		'verbs' => ['post'],
		'params' => '',
		'name' => '',
		'controller' => 'Activewebsite\EnterpriseCore\Controllers\UserManagement\AuthController', 
		'method' => 'processReset',
		'where' => '',
		'before' => '',
		'siteTypes' => ['company'],
		'roles' => [],
	],

	// complete reset
	[
		'uri' => '/admin/reset',
		'verbs' => ['get'],
		'params' => '/{id}/{code}',
		'name' => 'admin_reset_password',
		'controller' => 'Activewebsite\EnterpriseCore\Controllers\UserManagement\AuthController', 
		'method' => 'completeReset',
		'where' => '',
		'before' => '',
		'siteTypes' => ['company'],
		'roles' => [],
	],
	[
		'uri' => '/admin/reset',
		'verbs' => ['post'],
		'params' => '/{id}/{code}',
		'name' => '',
		'controller' => 'Activewebsite\EnterpriseCore\Controllers\UserManagement\AuthController', 
		'method' => 'processCompleteReset',
		'where' => '',
		'before' => '',
		'siteTypes' => ['company'],
		'roles' => [],
	],

	// activate account
	[
		'uri' => '/admin/activate',
		'verbs' => ['get'],
		'params' => '/{id}/{code}',
		'name' => 'admin_activate',
		'controller' => 'Activewebsite\EnterpriseCore\Controllers\UserManagement\AuthController', 
		'method' => 'activateAccount',
		'where' => '',
		'before' => '',
		'siteTypes' => ['company'],
		'roles' => [],
	],

];
