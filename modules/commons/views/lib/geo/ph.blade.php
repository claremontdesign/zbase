<?php

$provinces = require_once zbase_path_library('Geo/PH/state.php');
$provincesArray = [];
foreach ($provinces as $province => $cities)
{
	foreach ($cities as $city)
	{
		$c = [
			'id' => $city . ', ' . $province,
			'text' => $city . ', ' . $province
		];
		$provincesArray[] = $c;
	}
}
echo 'var PHCITIES =  ' . json_encode($provincesArray) . ';';