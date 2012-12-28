<?php

/*
	fctcodes
	========
	1	Clear	
	2	Partly Cloudy	
	3	Mostly Cloudy	
	4	Cloudy	
	5	Hazy	
	6	Foggy	
	7	Very Hot	
	8	Very Cold	
	9	Blowing Snow	
	10	Chance of Showers	
	11	Showers	
	12	Chance of Rain	
	13	Rain	
	14	Chance of a Thunderstorm	
	15	Thunderstorm	
	16	Flurries	
	17	OMITTED	
	18	Chance of Snow Showers	
	19	Snow Showers	
	20	Chance of Snow	
	21	Snow	
	22	Chance of Ice Pellets	
	23	Ice Pellets	
	24	Blizzard
*/

require_once("datefix.php");

class rainDataArray extends ArrayObject{ 
    public $data; 
    function __construct(){ 
        $this->data = new ArrayObject(); 
    } 
    
    function addObject($_index, $_data){ 
        $_thisItem = new rainData($_index, $_data); 
        $this->data->offSetSet($_index, $_thisItem); 
    } 
    function deleteObject($_index){ 
        $this->data->offsetUnset($_index); 
    } 
    function getObject($_index){ 
        $_thisObject = $this->data->offSetGet($_index); 
        return $_thisObject->getObject(); 
    } 

	 function slice($_start, $_length)
	 {
		 $_thisArray = $this->data->getArrayCopy();
		 return array_slice($_thisArray, $_start, $_length);
	 }
    function printCollection() { 
        print_r($this->data); 
    } 
} 

class rainData { 
    public $index;
    public $pop; 
	 public $prettytime;
	 public $datetime;
    
    function __construct($_index, $_data){ 
        $this->index = $_index; 
        $this->pop = $_data["pop"];
		  $this->datetime = $_data["datetime"]; 
		  $this->prettytime = $this->datetime->format('Y-m-d H:i:s');
    } 
    function printObject() { 
        print_r($this); 
    } 
}


class weatherData {
	private $rawjson;
	private $weather_data;
	
	private $rain_data;
	
	public $longest_rain_free_spell;
	public $next_rain_free_spell;
	public $currently_raining;
	public $next_rain_spell;
	public $longest_rain_spell;
	public $weather_status;
	
	private $pop_threshold = 40;

	public function __construct($json) 
	{
			$this->rawjson = $json;
			$this->decode();
			
			//print_r($this->rain_data);
			
			$this->find_rain_spells();
			$this->find_rain_free_spells();
			$this->currently_raining = ($this->rain_data->data[0]->pop >= $this->pop_threshold);
	}

	private function decode()
	{
		$this->weather_data = json_decode($this->rawjson);
		$this->rain_data = new rainDataArray();
		
		$index=0;
		foreach ($this->weather_data->hourly_forecast as $hour_report)
			$this->rain_data->addObject($index++, 
										array("datetime" => new DateTime($hour_report->FCTTIME->pretty), 
												"pop" => $hour_report->pop));
												
		$this->weatherStatus();
	}
	
	public function json()
	{
		return json_encode ( 
					array (
						"longest_rain_free_spell" => $this->longest_rain_free_spell->data,
						"next_rain_free_spell" => $this->next_rain_free_spell->data,
						"currently_raining" => $this->currently_raining,
						"next_rain_spell" => $this->next_rain_spell->data,
						"longest_rain_spell" => $this->longest_rain_spell->data,
						"raw" => $this->rain_data->slice(0,12)
					));
//				return json_encode ( $this->longest_rain_free_spell );
	}
	
	private function find_rain_free_spells()
	{
		$longest_length = 0;
		$current_length = 0;
		$longest_id;
		$current_id;
		$found = false;
		$first_found = true;
		
		$this->longest_rain_free_spell = new rainDataArray();
		
		foreach ($this->rain_data->data as $data)
		{

			if ($data->pop < $this->pop_threshold 
				 && 
				!($data->index==($this->rain_data->data->count()-1)))
			{
				if (!$found)
				{
					$found = true;
					$current_length = 1;
					$current_id = $data->index;
				}
				else {$current_length++;}
			}
			else
			{
				if ($found)
				{
					$current_length++;
					$found = false;

					if ($first_found)
					{
						$first_found=false;
						$this->next_rain_free_spell = new rainDataArray();
						$index=0;
						for ($id = $current_id; $id < $current_id + $current_length; $id++)
									$this->next_rain_free_spell->addObject($index++, 
																		array("datetime" => $this->rain_data->data[$id]->datetime, 
																		"pop" => $this->rain_data->data[$id]->pop));
					}

					if ($current_length>$longest_length)
					{
						$longest_id = $current_id;
						$longest_length = $current_length;
					}
				}
				else $found = false;
			}
		}
		
		$index=0;
		for ($id = $longest_id; $id < $longest_id + $longest_length; $id++)
		{
					$this->longest_rain_free_spell->addObject($index++, 
														array("datetime" => $this->rain_data->data[$id]->datetime, 
														"pop" => $this->rain_data->data[$id]->pop));
														
			
		}

	}
	
	
	private function find_rain_spells()
	{
		$longest_length = 0;
		$current_length = 0;
		$longest_id;
		$current_id;
		$found = false;
		$first_found = true;
		
		$this->longest_rain_spell = new rainDataArray();
		
		foreach ($this->rain_data->data as $data)
		{

			if ($data->pop >= $this->pop_threshold 
				 && 
				!($data->index==($this->rain_data->data->count()-1)))
			{
				if (!$found)
				{
					$found = true;
					$current_length = 1;
					$current_id = $data->index;
				}
				else {$current_length++;}
			}
			else
			{
				if ($found)
				{
					$current_length++;
					$found = false;

					if ($first_found)
					{
						$first_found=false;
						$this->next_rain_spell = new rainDataArray();
						$index=0;
						for ($id = $current_id; $id < $current_id + $current_length; $id++)
									$this->next_rain_spell->addObject($index++, 
																		array("datetime" => $this->rain_data->data[$id]->datetime, 
																		"pop" => $this->rain_data->data[$id]->pop));
						
																		
					}

					if ($current_length>$longest_length)
					{
						$longest_id = $current_id;
						$longest_length = $current_length;
					}
				}
				else $found = false;
			}
		}
		
		$index=0;
		for ($id = $longest_id; $id < $longest_id + $longest_length; $id++)
					$this->longest_rain_spell->addObject($index++, 
													array("datetime" => $this->rain_data->data[$id]->datetime, 
													"pop" => $this->rain_data->data[$id]->pop));

	}
	
	private function weatherStatus()
	{
		$status = 0;
		
		if($this->currently_raining)
		{
			$status = 1; //If raining return "Bad"
			if (isset($this->next_rain_free_spell->data))
			{
				// $rain_duration = $this->rain_data->data[0]->datetime->diff($this->next_rain_free_spell->data[0]->datetime);
				// 				if ($rain_duration < (new DateInterval("PT1H")))
				// 					$status = 3; //If getting better soon return "Good->Bad"
				$rain_duration = DiffInHours($this->next_rain_free_spell->data[0]->datetime, $this->rain_data->data[0]->datetime);
				if ($rain_duration < 2)
					$status = 3;
			}
			
		}
		else 
		{
			$status = 0; //If dry return "Good"
			if (isset($this->next_rain_spell->data))
			{
				// $dry_duration = $this->rain_data->data[0]->datetime->diff($this->next_rain_spell->data[0]->datetime);
				// if ($dry_duration < (new DateInterval("PT1H")))
				// 	$status = 2; //If getting wet soon return "Good->Bad"
				$dry_duration = DiffInHours($this->next_rain_spell->data[0]->datetime,$this->rain_data->data[0]->datetime);
				if ($dry_duration < 2)
					$status = 2;
			}
		}
		
		$this->weather_status = $status;
		
	}

}


?>
