<?php namespace Booj\EnterpriseCore\Models\Seo;

use Illuminate\Support\Facades\Config;

class Seo {

	protected $pageDescription;
	protected $pageTitle;
	protected $pageKeywords;

	/**
	 * Constructor
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setDefaults();
	}

	/**
	 * Set default title, description and keywords from config file
	 *
	 * @return  void
	 */
	public function setDefaults()
	{
		// set our defaults
		$this->setPageTitle(Config::get('enterpriseCore.default_page_title'));
		$this->setPageDescription(Config::get('enterpriseCore.default_page_description'));
		$this->setPageKeywords(Config::get('enterpriseCore.default_page_keywords'));
	}

	/**
	 * Set the page title
	 * 
	 * @param string $description
	 * @param boolean $replace
	 * @return void
	 */
	public function setPageTitle($title = '', $replace = true, $delimiter = '|')
	{
		if ($replace) {
			$this->pageTitle = trim($title);
		} else {
			$this->pageTitle .= ' ' . $delimiter . ' ' . trim($title);
		}
	}

	/**
	 * Gets the page title
	 * 
	 * @return string
	 */
	public function getPageTitle()
	{
		return str_replace('"', '', $this->pageTitle);
	}

	/**
	 * Set the page keywords
	 * 
	 * @param string $keywords
	 * @param boolean $replace
	 * @return void
	 */
	public function setPageKeywords($keywords = '', $replace = true)
	{
		if ($replace) {
			$this->pageKeywords = trim($keywords);
		} else {
			$this->pageKeywords .= ', ' . trim($keywords);
		}
	}

	/**
	 * Gets the page keywords
	 * 
	 * @return string
	 */
	public function getPageKeywords()
	{
		return str_replace('"', '', $this->pageKeywords);
	}	

	/**
	 * Set the page description
	 * 
	 * @param string $description
	 * @param boolean $replace
	 * @return void
	 */
	public function setPageDescription($description = '', $replace = true)
	{
		if ($replace) {
			$this->pageDescription = trim($description);
		} else {
			$this->pageDescription .= ' ' . trim($description);
		}
	}

	/**
	 * Gets the page description
	 * 
	 * @return string
	 */
	public function getPageDescription()
	{
		return str_replace('"', '', $this->pageDescription);
	}

}
