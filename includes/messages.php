<?php

// Programming natural text is harder than I thought it'd be. Bugger.

/*
	Light Status
	=============
	0  Good
	1  Bad
	2  Good->Bad
	3  Bad->Good

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

class wtdMessage
{

	private static $weatherMsg1 =  array ( 0,
									array ( "Looks great" , 1 ), // 1
									array ( "Looks great" , 1 ), // 2
									array ( "Looks a bit cloudy" , 0 ), // 3
									array ( "Looks a bit cloudy" , 0 ), // 4
									array ( "Doesn't looks great" , 0 ), // 5
									array ( "Doesn't looks great" , 0 ), // 6
									array ( "It's going to be hot" , 0 ), // 7
									array ( "It's going to be cold" , 0 ), // 8
									array ( "Looks like snow out there" , 0 ), // 9
									array ( "It could be a bit wet" , 0 ), // 10
									array ( "It's looking kind of wet" , 0 ), // 11
									array ( "It could be a bit wet" , 0 ), // 12
									array ( "It's looking kind of wet" , 0 ), // 13
									array ( "It could be a bit wet" , 0 ), // 14
									array ( "It's looking kind of wet" , 0 ), // 15
									array ( "Looks like snow out there" , 0 ), // 16
									0, // 17
									array ( "It could be a bit wet" , 0 ), // 18
									array ( "It's looking kind of wet" , 0 ), // 19
									array ( "Looks like snow out there" , 0 ), // 20
									array ( "Looks like snow out there" , 0 ), // 21
									array ( "It could be a bit wet" , 0 ), // 22
									array ( "It's looking kind of wet" , 0 ), // 23
									array ( "Looks like snow out there" , 0 ), // 24
	 							);
	
	private static $weatherMsg2 =  array ( 0,
									array ( " should be nice later" , 1 ), // 1
									array ( " should be nice later" , 0 ), // 2
									array ( " a little cloudy later" , 0 ), // 3
									array ( " a little cloudy later" , 0 ), // 4
									array ( " a bit misty later" , 0 ), // 5
									array ( " a bit misty later" , 0 ), // 6
									array ( " a bit hot later" , 0 ), // 7
									array ( " a bit hot later" , 0 ), // 8
									array ( " snow to come" , 0 ), // 9
									array ( " a chance of rain to come" , 0 ), // 10
									array ( " pretty wet" , 0 ), // 11
									array ( " a chance of rain to come" , 0 ), // 12
									array ( " pretty wet" , 0 ), // 13
									array ( " a chance of rain to come" , 0 ), // 14
									array ( " pretty wet" , 0 ), // 15
									array ( " snow to come" , 0 ), // 16
									0, // 17
									array ( " a chance of rain to come" , 0 ), // 18
									array ( " pretty wet" , 0 ), // 19
									array ( " snow to come" , 0 ), // 20
									array ( " snow to come" , 0 ), // 21
									array ( " a chance of rain to come" , 0 ), // 22
									array ( " pretty wet" , 0 ), // 23
									array ( " snow to come" , 0 ), // 24
	 							);
	
	// structor $weatherConjunction[weather_now_bad/good][weather_next_bad/good]
	private static $weatherConjunction = array (	array (" with", " but"), 
														array ( " but", " and"));
													
	private static $lightMessage = array ( "" ,
												" it's going to be dark outside",
												" it's going to start getting dark soon",
												" it should be getting light outside soon");
										
	// lightConjunction[light code][weather_next_bad/good]							
	private static $lightConjunction = array ( 
													array( "", "" ),
													array( " and", ", although" ),
													array( " and", ", although" ),
													array( " but", " and" )
												  );
	
	public function GenerateMessage( $_light_code, $_fctcode, $_fctcode_next)
	{		
		$msg_1 = self::$weatherMsg1[$_fctcode][0];
		
		$msg_2 = "";
		$weather_conjunction = "";
		if ($_fctcode!=$_fctcode_next)
		{
			$msg_2 = self::$weatherMsg2[$_fctcode_next][0];
			$weather_conjunction = self::$weatherConjunction[self::$weatherMsg1[$_fctcode][1]][self::$weatherMsg1[$_fctcode_next][1]];
		}

		$msg_3 = self::$lightMessage[$_light_code];
		$light_conjunction = self::$lightConjunction[$_light_code][self::$weatherMsg1[$_fctcode_next][1]];
		
		return $msg_1 . $weather_conjunction . $msg_2 . $light_conjunction . $msg_3 .".";	
	}
	
}


?>

