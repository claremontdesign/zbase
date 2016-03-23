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

class NodeController extends Controller
{

	/**
	 * Serve a Node Image
	 * @return Response
	 */
	public function image()
	{
		$node = zbase_route_input('node', null);
		$id = zbase_route_input('id', null);
		if(!empty($node) && !empty($id))
		{
			$entity = zbase_entity($node . '_files', [], true);
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
