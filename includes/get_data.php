<?php
require_once('astrodata.class.php');
require_once('weatherdata.class.php');
require_once('messages.php');
require_once('apikeys.php');

if (isset($_GET['city']) && isset($_GET['region'])) 
{
	$city = $_GET['city'];
	$region = $_GET['region'];
}
else
{
	$city = "Glasgow";
	$region = "United Kingdom";
}


$url_weather = "http://api.wunderground.com/api/".$api_key."/hourly/q/".
			rawurlencode($region)."/".rawurlencode($city).".json";

$url_astro = "http://api.wunderground.com/api/".$api_key."/astronomy/q/".
			rawurlencode($region)."/".rawurlencode($city).".json";

// Comment out to use local json copy
// $url_weather = "../json/weather.json";
// $url_astro = "../json/astro.json";

$weatherData = new WeatherData(file_get_contents($url_weather));
$astroData = new AstroData(file_get_contents($url_astro));


echo '{"message":'.json_encode($wtdMessages[$astroData->astro_status][$weatherData->weather_status]).', "astroData":'.$astroData->json().' , "weatherData":'.$weatherData->json().'}';

?>