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
		if ($this->current_time > $this->sunrise_time)
			$this->sunrise_time->modify('+1 day');
			
		$this->sunset_time = new DateTime($this->astro_data->moon_phase->sunset->hour.':'.$this->astro_data->moon_phase->sunset->minute);
		if ($this->current_time > $this->sunset_time)
			$this->sunset_time->modify('+1 day');
		
		// if ($this->current_time < $this->sunrise_time)
		// {
		// 	$this->sunrise_interval = $this->current_time->diff($this->sunrise_time);
		// 	$this->sunset_interval = $this->current_time->diff($this->sunset_time);
		// }
		// else
		// {
		// 	$sunrise_time = clone $this->sunrise_time;
		// 	$sunset_time = clone $this->sunset_time;
		// 	$this->sunrise_interval = date_diff($sunrise_time->add($this->current_time->diff(new DateTime("24:00"))), new DateTime("0:00"));
		// 	$this->sunset_interval = date_diff($sunset_time->add($this->current_time->diff(new DateTime("24:00"))), new DateTime("0:00"));
		// }
		
		$this->sunrise_interval = DiffInHours($this->sunrise_time, $this->current_time);
		$this->sunset_interval = DiffInHours($this->sunset_time, $this->current_time);

		if ($this->current_time > $this->sunrise_time && $this->current_time < $this->sunset_time)
			 $this->daylight = true;
		else $this->daylight = false;
				
	}
	
	public function json()
	{
		return json_encode ($this);
	}
	
	public function astroStatus()
	{
		$status = 1;
		
		if ($this->daylight)
		{
			$status = 0; //if it's daylight retrun "Good"

			if ($this->sunset_interval < 2)
				$status = 2; //if getting dark soon return "Good->Bad"
		}
		else
		{
			$status = 1; //if dark return "Bad"

			if ($this->sunrise_interval < 2)
				$status = 3; //if getting light soon return "Bad->Good"
		}

		return $status;
		
	}

}


?>
