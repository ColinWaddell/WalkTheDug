<?php

require_once("datefix.php");

class astroData {
	private $rawjson;
	private $astro_data;
	
	public $current_time;
	public $sunrise_time;
	public $sunset_time;
	public $sunrise_interval;
	public $sunset_interval;
	public $daylight;

	public $astro_status;

	public function __construct($json = '') { //Optional parameter
		if ($json!="")
		{
			$this->rawjson = $json;
			$this->decode();
		}

	}

	private function decode() {
		$this->astro_data = json_decode($this->rawjson);
		
		$this->current_time = new DateTime($this->astro_data->moon_phase->current_time->hour.':'.$this->astro_data->moon_phase->current_time->minute);
		
		$this->sunrise_time = new DateTime($this->astro_data->moon_phase->sunrise->hour.':'.$this->astro_data->moon_phase->sunrise->minute);
		$this->sunset_time = new DateTime($this->astro_data->moon_phase->sunset->hour.':'.$this->astro_data->moon_phase->sunset->minute);

		if ($this->current_time > $this->sunrise_time && $this->current_time < $this->sunset_time)
			 $this->daylight = 1;
		else $this->daylight = 0;

		//fix times for correct interval calculation

		if ($this->current_time > $this->sunrise_time)
			$this->sunrise_time->modify('+1 day');
			
		if ($this->current_time > $this->sunset_time)
			$this->sunset_time->modify('+1 day');

		
		$this->sunrise_interval = DiffInHours($this->sunrise_time, $this->current_time);
		$this->sunset_interval = DiffInHours($this->sunset_time, $this->current_time);

	
		$this->astroStatus();
				
	}
	
	public function json()
	{
		return json_encode ($this);
	}
	
	private function astroStatus()
	{
		$status = 1;
		
		if ($this->daylight)
		{
			$status = 0; //if it's daylight retrun "Good"

			if ($this->sunset_interval <= 1 || $this->sunset_interval == 24)
				$status = 2; //if getting dark soon return "Good->Bad"
		}
		else
		{
			$status = 1; //if dark return "Bad"

			if ($this->sunrise_interval <= 1 || $this->sunrise_interval == 24)
				$status = 3; //if getting light soon return "Bad->Good"
		}

		$this->astro_status = $status;
		
	}

}


?>
