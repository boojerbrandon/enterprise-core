<?php namespace Activewebsite\EnterpriseCore\Tests;

use Activewebsite\EnterpriseCore\Models\Seo\Seo;
use PHPUnit_Framework_TestCase;
use Mockery as m;

class SearchTest extends PHPUnit_Framework_TestCase {
	
	/** Mock the config facade */
	public function mock_configs()
	{
		$app = m::mock('AppMock');
		$app->shouldReceive('instance')->once()->andReturn($app);

		\Illuminate\Support\Facades\Facade::setFacadeApplication($app);
		\Illuminate\Support\Facades\Config::swap($config = m::mock('ConfigMock'));

		$config->shouldReceive('get')->once()
			->with('enterpriseCore.default_page_title')
			->andReturn('Mock Title')
		;
		
		$config->shouldReceive('get')->once()
			->with('enterpriseCore.default_page_description')
			->andReturn('Mock Description')
		;

		$config->shouldReceive('get')->once()
			->with('enterpriseCore.default_page_keywords')
			->andReturn('Mock Keywords')
		;
	}

	/** @test */
	public function can_we_make_an_seo_model()
	{
		$this->mock_configs();
		$obj = new Seo();
		$this->assertInstanceOf('Activewebsite\EnterpriseCore\Models\Seo\Seo', $obj);
	}

	/** @test */
	public function set_page_title()
	{
		$this->mock_configs();
		$seo = new Seo();
		$seo->setPageTitle('test');
		$this->assertEquals('test', $seo->getPageTitle());
	}

	/** @test */
	public function set_page_title_with_append()
	{
		$this->mock_configs();
		$seo = new Seo();
		$before = $seo->getPageTitle();
		$seo->setPageTitle('test', false);
		$this->assertEquals($before . ' | test', $seo->getPageTitle());
	}

	/** @test */
	public function set_page_title_with_append_and_delimeter()
	{
		$this->mock_configs();
		$seo = new Seo();
		$before = $seo->getPageTitle();
		$seo->setPageTitle('test', false, '~');
		$this->assertEquals($before . ' ~ test', $seo->getPageTitle());
	}

	/** @test */
	public function set_page_description()
	{
		$this->mock_configs();
		$seo = new Seo();
		$seo->setPageDescription('test');
		$this->assertEquals('test', $seo->getPageDescription());
	}

	/** @test */
	public function set_page_description_with_append()
	{
		$this->mock_configs();
		$seo = new Seo();
		$before = $seo->getPageDescription();
		$seo->setPageDescription('test', false);
		$this->assertEquals($before . ' test', $seo->getPageDescription());
	}

	/** @test */
	public function set_page_keywords()
	{
		$this->mock_configs();
		$seo = new Seo();
		$seo->setPageKeywords('test');
		$this->assertEquals('test', $seo->getPageKeywords());
	}

	/** @test */
	public function set_page_keywords_with_append()
	{
		$this->mock_configs();
		$seo = new Seo();
		$before = $seo->getPageKeywords();
		$seo->setPageKeywords('test', false);
		$this->assertEquals($before . ', test', $seo->getPageKeywords());
	}

}
