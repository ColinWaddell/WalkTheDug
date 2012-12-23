<?php

if (isset($_GET['location'])) 
{
	$location = $_GET['location'];
	if (strlen($location)>3)
	{
		$url_autocomplete = "http://autocomplete.wunderground.com/aq?query=".$location;
		
		$auto_data = json_decode(file_get_contents($url_autocomplete));
		
		if ($auto_data != null)
		{
			echo '{"results":[';
			$index = 0;
			foreach ($auto_data->RESULTS as $data)
			{
				if ($index++) echo ",";
				echo '{"name":"'.$data->name.'"}';
			}
				
			echo "]}";
		}else echo "}";
		
		
	}
}else echo "{}";

?>