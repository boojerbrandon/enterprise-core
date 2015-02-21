<?php namespace Booj\EnterpriseCore\Models;

use Illuminate\Support\Facades\Request;

class Site {

	protected $site_type;
	protected $site_owner;
	protected $domain;

	public function __construct()
	{

	}

	public function bootstrap()
	{
		$this->domain = Request::root();

		/**
		 * query a domains table that will give use the site type and who the
		 * owner of that site is based on $this->domain. ie the realtor or company or office
		 * when we figure that out, set the site_type and site_owner
		 */
		$this->setSiteType('company');
		$this->setSiteOwner(null);
	}

	public function getTypeByDomain()
	{

	}

	public function setSiteType($type = '') 
	{
		$this->site_type = $type;
	}

	public function getSiteType()
	{
		return $this->site_type;
	}

	public function setSiteOwner($owner = '') 
	{
		$this->site_owner = $owner;
	}

	public function getSiteOwner()
	{
		return $this->site_owner;
	}

}
