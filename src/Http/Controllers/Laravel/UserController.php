<?php

namespace Zbase\Http\Controllers\Laravel;

/**
 * PageController
 *
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file PageController.php
 * @project Zbase
 * @package Zbase\Http\Controllers
 */
use Zbase\Http\Controllers\Laravel\Controller;

class UserController extends Controller
{

	/**
	 * User Page
	 */
	public function username()
	{

	}

	/**
	 * Serve a Node Image
	 * @return Response
	 */
	public function image()
	{
		$id = zbase_route_input('id', null);
		if(!empty($id))
		{
			$entity = zbase_entity('user', [], true);
			if(!empty($entity))
			{
				$entity = $entity->repository()->byAlphaId($id);
				if(!empty($entity))
				{
					return $entity->serveImage(zbase_route_input('w'), zbase_route_input('h'), zbase_route_input('q'));
				}
			}
		}
		return $this->notfound();
	}
}
