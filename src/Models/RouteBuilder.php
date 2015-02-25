<?php namespace Booj\EnterpriseCore\Models;

use Booj\EnterpriseCore\Facades\SiteFacade as Site;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;

class RouteBuilder {

	public function __construct()
	{
		// bootstrap our siteowner
		Site::bootstrap();
	}


	public function getRoutes()
	{
		$type = Site::getSiteType();
		$owner = Site::getSiteOwner();

		$query = "SELECT * from routes 
		          WHERE site_types 
		          LIKE '%{$type}%' 
		          OR site_types = 'all'";

		$results = \DB::select($query);

		$user = Sentinel::getUser();

		foreach($results as $route)
		{
			$verbs = explode(',',$route->verbs);
			$types = explode(',',$route->site_types);
			$roles = explode(',',$route->roles);
			$uri = $route->uri . $route->params;
			$extra = ['uses' => $route->controller . '@' . $route->method];
			if($route->name != "") $extra['as'] = $route->name;
			if($route->before != "") $extra['before'] = $route->before;
			if($roles[0] == "") $roles = [];
			if(count($roles))
			{
				foreach($roles as $role)
				{
					if($user && $user->inRole($role))
					{
						Route::match($verbs,$uri,$extra);
						break;
					}
				}
			}
			else
			{
				Route::match($verbs,$uri,$extra);
			}
		}	
	}
}