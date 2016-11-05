<?php

$countries = require_once zbase_path_library('Geo/countries.php');
$countriesArray = [];
foreach ($countries as $code => $name)
{
	$c = [
		'id' => $code,
		'text' => ucwords(strtolower($name))
	];
	$countriesArray[] = $c;
}
echo 'var COUNTRIES =  ' . json_encode($countriesArray) . ';';
