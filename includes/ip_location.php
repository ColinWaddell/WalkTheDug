<?php

require_once('geoplugin.class.php');

$geoplugin = new geoPlugin();

//locate the IP
$geoplugin->locate();

$city = $geoplugin->city;
if ($geoplugin->countryName == "United States")
	  $region = $geoplugin->region;
else $region = $geoplugin->countryName;

echo	json_encode( array ( "city" => $city, "region" => $region));

?>