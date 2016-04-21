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
		$modules = zbase()->modules();
		foreach ($modules as $module)
		{
			$this->assertTrue(zbase()->module($module->id()) instanceof \Zbase\Module\ModuleInterface);
		}
	}

}
