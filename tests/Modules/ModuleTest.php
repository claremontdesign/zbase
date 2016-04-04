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
		zbase()->loadModuleFrom(__DIR__ . '/../config/modules');
		zbase_routes_init();
		$this->assertTrue(zbase()->module('nodes') instanceof \Zbase\Module\ModuleInterface);
		$this->assertSame(\Config::get('app.url') . '/admin/nodes', zbase_url_from_route('admin.nodes'));
	}

}
