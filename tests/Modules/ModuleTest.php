<?php

/**
 */
class ModuleTest extends TestCase
{

	/**
	 * Added module can be added dynamically to route
	 * and can be accessed
	 * @group Modules
	 */
	function testModuleRoute()
	{
		zbase()->addModule('testModule', __DIR__ . '/../config');
		zbase_routes_init();
		$this->assertTrue(zbase()->module('testModule') instanceof \Zbase\Module\ModuleInterface);
		$this->assertSame('http://localhost/admin/testModule', zbase_url_from_route('admin.testModule'));
	}

}
