<?php namespace Booj\EnterpriseCore\Models\Analytics;

use Illuminate\Support\Facades\Config;

class AnalyticCodes {

	protected $pageName;

	/**
	 * Constructor
	 * 
	 * @return void
	 */
	public function __construct()
	{

	}

	/**
	 * Set the page view
	 * 
	 * @param string $name
	 */
	public function setPageName($name = '')
	{
		$this->pageName = $name;
	}

	/**
	 * Gets the page name
	 * 
	 * @return string
	 */
	public function getPageName()
	{
		return $this->pageName;
	}	

	/**
	 * Writes the google analytics code
	 * 
	 * @param  boolean $display_features
	 * @return string
	 */
	public function getGACode($display_features = false)
	{
		$ga_code = Config::get('enterpriseCore.google_analytics_code');
		$pagename = $this->getPageName();

		if (!$ga_code) {
			return '<!-- no google analytics code defined -->';
		}

		$script = array("<script type=\"text/javascript\">", "(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');");
		$script[] = 'ga(\'create\', \'' . $ga_code . '\', \'auto\');';
		
		if ($display_features) {
			$script[] = 'ga(\'require\', \'displayfeatures\');';
		}

		if ($pagename != "") {
			$script[] = 'ga(\'send\', \'pageview\', {\'page\': \'' . $pagename . '\'});';
		} else {
			$script[] = 'ga(\'send\', \'pageview\');';
		}

		$script[] = 'ga(\'create\', \'UA-28710577-1\', \'auto\', {\'name\': \'boojTracker\'});';
		$script[] = 'ga(\'boojTracker.send\', \'pageview\');';
		$script[] = '</script>';

		return implode('', $script);
	}

	/**
	 * Writes google tag manager code
	 * 
	 * @return string
	 */
	public function getGTMCode()
	{
		$gtm_code = Config::get('enterpriseCore.google_tag_manager_code');
		
		if (!$gtm_code) {
			return '<!-- no google tag manager code defined -->';
		}

		return "<noscript><iframe src=\"//www.googletagmanager.com/ns.html?id={$gtm_code}\" height=\"0\" width=\"0\" style=\"display:none;visibility:hidden\"></iframe></noscript><script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','{$gtm_code}');</script>";
	}

}
