<?php

$countries = require __DIR__ . '/countriesData.php';
$data = [];
foreach ($countries as $countryCode => $country)
{
	$data[$countryCode] = $country['name'];
}
return $data;
