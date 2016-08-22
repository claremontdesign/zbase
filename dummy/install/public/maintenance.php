<?php

if(!empty($pathToLaravel))
{
	$zbasePackage = $pathToLaravel . '/vendor/claremontdesign/zbase';
}
else
{
	$pathToLaravel = __DIR__ . '/..';
	$zbasePackage = $pathToLaravel . '/packages/dennesabing/zbase';
}

if(file_exists($pathToLaravel . '/storage/maintenance'))
{
	if(file_exists($pathToLaravel . '/storage/maintenance_schedule'))
	{
		$details = json_decode(file_get_contents($pathToLaravel . '/storage/maintenance_schedule'), true);
		if(!empty($details['maintenance-ips']))
		{
			$ips = explode("\n", $details['maintenance-ips']);
			foreach ($ips as $i => $ip)
			{
				if(trim($_SERVER['REMOTE_ADDR']) == trim($ip))
				{
					$excluded = true;
					break;
				}
			}
		}
	}
	if(empty($excluded))
	{
		if(!file_exists(__DIR__ . '/maintenance.html'))
		{
			copy($zbasePackage . '/resources/views/contents/maintenance/maintenance.html', __DIR__ . '/maintenance.html');
		}
		header('HTTP/1.1 503 Service Temporarily Unavailable');
		header('Status: 503 Service Temporarily Unavailable');
		header('Retry-After: 3600');
		include __DIR__ . '/maintenance.html';
		exit;
	}
}