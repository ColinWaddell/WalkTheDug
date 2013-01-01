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

// DiffInHours($this->next_rain_spell->data[0]->datetime,$this->rain_data->data[0]->datetime);


require_once("datefix.php");

class weatherDataArray extends ArrayObject{ 
    public $data; 
    function __construct(){ 
        $this->data = new ArrayObject(); 
    } 
    
    function addObject($_index, $_data){ 
        $_thisItem = new weatherDatum($_index, $_data); 
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

class weatherDatum { 
    public $index;
    public $pop; 
	 public $fctcode;
	 public $prettytime;
	 public $datetime;
    
    function __construct($_index, $_data){ 
        $this->index = $_index; 
        $this->pop = $_data["pop"];
		  $this->datetime = $_data["datetime"]; 
		  $this->prettytime = $this->datetime->format('Y-m-d H:i:s');
		  $this->fctcode = $_data["fctcode"]; 
    } 
    function printObject() { 
        print_r($this); 
    } 
}


class weatherData {
	private $rawjson;
	private $weather_data;
	private $decoded_data;
	
	
	public $fctcode = 0;
	public $fctcode_next = 0;

	public function __construct($json) 
	{
			$this->rawjson = $json;
			$this->decode();
	}

	private function decode()
	{
		$this->decoded_data = json_decode($this->rawjson);
		$this->weather_data = new weatherDataArray();
		
		$index=0;
		foreach ($this->decoded_data->hourly_forecast as $hour_report)
			$this->weather_data->addObject($index++, 
										array("datetime" => new DateTime($hour_report->FCTTIME->pretty), 
												"pop" => $hour_report->pop,
												"fctcode" => $hour_report->fctcode));
	
		$this->fctcode = $this->weather_data->data[0]->fctcode;
		
		$this->fctcode_next = (	$this->fctcode == $this->weather_data->data[1]->fctcode
											?
										$this->weather_data->data[2]->fctcode : $this->weather_data->data[1]->fctcode);
		
	}
	
	public function json()
	{
		return json_encode ( 
					array (
						"pop" => $this->weather_data->slice(0,12),
						"fctcode" => $this->fctcode,
						"fctcode_next" => $this->fctcode_next
					));
	}
	
	public function get_fctcode()
	{
		return $this->fctcode;
	}

	public function get_fctcode_next()
	{
		return $this->fctcode_next;
	}
	

}


?>
