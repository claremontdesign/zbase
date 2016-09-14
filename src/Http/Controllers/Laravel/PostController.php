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

class PostController extends Controller
{

	/**
	 * Serve a Post File
	 * @return Response
	 */
	public function file()
	{
		$table = zbase_route_input('table', null);
		$action = zbase_route_input('action', null);
		$id = zbase_route_input('id', null);
		$file = zbase_route_input('file', null);

		if(!empty($table) && !empty($action) && !empty($id) && !empty($file))
		{
			$entityObject = zbase_entity($table);
			if($entityObject instanceof \Zbase\Post\PostInterface)
			{
				$entity = $entityObject->postById($id);
				if(!empty($entity))
				{
					if($entity->postUserHasAccess())
					{
						if($action == 'delete' && zbase_is_post())
						{
							$file = $entity->postFileByFilename($file);
							if(!empty($file) && $entity->postFileCanBeDeleted($file))
							{
								return $entity->postFileDelete($file);
							}
						}
						if($action == 'view')
						{
							$width = null;
							$height = null;
							if(preg_match('/_/', $file) > 0)
							{
								$filex = explode('_', $file);
								if((count($filex) == 1) && !empty($filex[0]))
								{
									$filename = $filex[0];
								}
								if((count($filex) == 2) && !empty($filex[1]))
								{
									$sizeX = explode('x', $filex[0]);
									$width = $sizeX[0];
									$height = $sizeX[1];
									$filename = $filex[1];
								}
							}
							else
							{
								$filename = $file;
							}
							if(!empty($filename))
							{
								$file = $entity->postFileByFilename($filename);
								if(!empty($file))
								{
									return $entity->postFileServe($file, $width, $height);
								}
							}
						}
					}
				}
			}
		}
		return $this->notfound();
	}

	public function filetmp()
	{
		$table = zbase_route_input('table', null);
		$action = zbase_route_input('action', null);
		$file = zbase_route_input('file', null);

		if(!empty($table) && !empty($action) &&  !empty($file))
		{
			$entity = zbase_entity($table);
			if($entity instanceof \Zbase\Post\PostInterface)
			{
				if($action == 'view')
				{
					$width = null;
					$height = null;
					if(preg_match('/_/', $file) > 0)
					{
						$filex = explode('_', $file);
						if((count($filex) == 1) && !empty($filex[0]))
						{
							$filename = $filex[0];
						}
						if((count($filex) == 2) && !empty($filex[1]))
						{
							$sizeX = explode('x', $filex[0]);
							$width = $sizeX[0];
							$height = $sizeX[1];
							$filename = $filex[1];
						}
					}
					else
					{
						$filename = $file;
					}
					if(!empty($filename))
					{
						$file = $entity->postFileByFilenameTmp($filename);
						if(!empty($file))
						{
							return $entity->postFileServe($file, $width, $height);
						}
					}
				}
			}
		}
		return $this->notfound();
	}

}
