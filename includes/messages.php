<?php

// structured as wtdMessages [ Light Status ] [ Rain Status ]

// Status	0: Good
// 			1: Bad
// 			2: Good->Bad
// 			3: Bad->Good

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

class wtdMessage
{
	
	public function GenerateMessage( $_light_code, $_fctcode, $_fctcode_next)
	{
		$msg_1 = "";
		$msg_2 = "";
		$msg_3 = "";
		
		$weather_conjunction = "";
		$light_conjunction = "";
		
		$good_news_now = true;
		$good_news_next = true;
			
		switch ($_fctcode)
		{
			case 1: //Clear	
			case 2: //Partly Cloudy	
				$msg_1 .= "Looks great";
				$good_news_now = true;
				break;
				
			case 3: //Mostly Cloudy	
			case 4: //Cloudy
				$msg_1 .= "Looks a bit cloudy";
				$good_news_now = false;
				break;
				
			case 5: //Hazy
			case 6: //Foggy
				$msg_1 .= "Doesn't looks great";
				$good_news_now = false;
				break;
				
			case 7: //Very Hot
				$msg_1 .= "It's going to be hot";
				$good_news_now = false;
				break;
				
			case 8: //	Very Cold	
				$msg_1 .= "It's going to be cold";
				$good_news_now = false;
				break;
				
			case 10: //	Chance of Showers	
			case 12: //	Chance of Rain	
			case 14: //	Chance of a Thunderstorm	
			case 18: //	Chance of Snow Showers	
			case 22: //	Chance of Ice Pellets	
				$msg_1 .= "It could be a bit wet";
				$good_news_now = false;
				break;
				
			case 11: //	Showers	
			case 13: //	Rain	
			case 15: //	Thunderstorm	
			case 19: //	Snow Showers	
			case 23: //	Ice Pellets
				$msg_1 .= "It looking kind of wet";
				$good_news_now = false;
				break;

			case 9: //	Blowing Snow
			case 16: //	Flurries	
			case 20: //	Chance of Snow	
			case 21: //	Snow	
			case 24: //	Blizzard
				$msg_1 .= "Looks like snow out there";
				$good_news_now = false;
				break;
		}
		
		if ($_fctcode!=$_fctcode_next)
		{
			switch ($_fctcode_next)
			{
				case 1: //Clear	
				case 2: //Partly Cloudy	
					$msg_2 .= " should be nice later";
					$good_news_next = true;
					break;

				case 3: //Mostly Cloudy	
				case 4: //Cloudy
					$msg_2 .= " a little cloudy later";
					$good_news_next = false;
					break;

				case 5: //Hazy
				case 6: //Foggy
					$msg_2 .= " a bit misty later";
					$good_news_next = false;
					break;

				case 7: //Very Hot
					$msg_2 .= " a bit hot later";
					$good_news_next = false;
					break;

				case 8: //	Very Cold	
					$msg_2 .= " cold later";
					$good_news_next = false;
					break;

				case 10: //	Chance of Showers	
				case 12: //	Chance of Rain	
				case 14: //	Chance of a Thunderstorm	
				case 18: //	Chance of Snow Showers	
				case 22: //	Chance of Ice Pellets	
					$msg_2 .= " a chance of rain to come";
					$good_news_next = false;
					break;

				case 11: //	Showers	
				case 13: //	Rain	
				case 15: //	Thunderstorm	
				case 19: //	Snow Showers	
				case 23: //	Ice Pellets
					$msg_2 .= " pretty wet";
					$good_news_next = false;
					break;

				case 9: //	Blowing Snow
				case 16: //	Flurries	
				case 20: //	Chance of Snow	
				case 21: //	Snow	
				case 24: //	Blizzard
						$msg_2 .= " snow to come";
						$good_news_next = false;
						break;
			}
			
			if ($good_news_now && $good_news_next)
				$weather_conjunction = " and";
			if ($good_news_now && !$good_news_next)
					$weather_conjunction = " but";
			if (!$good_news_now && $good_news_next)
				$weather_conjunction = " but";
			if (!$good_news_now && !$good_news_next)
				$weather_conjunction = " and";
			
		}
		
		
		switch ($_light_code)
		{
			case 1:
				$msg_3 .= " it's going to be dark outside";
				$light_conjunctions = ($good_news_next ? ", although" : " and"); 
				break;
			case 2:
				$msg_3 .= " it's going to start getting dark soon";
				$light_conjunctions = ($good_news_next ? ", although" : " and");
				break;
			case 3:
				$msg_3 .= " it should be getting light outside soon";
				$light_conjunctions = ($good_news_next ? " and" : " but");
				break;
		}
		
		return $msg_1.$weather_conjunction.$msg_2.$light_conjunctions.$msg_3;
		
	}
	
}


$wtdMessages = array (  array (	"Looks OK - go walk the dog.", // Light = 0 - Rain = 0
											"It's going to be raining for a wee bit, might as well walk the dog.", // Light = 0 - Rain = 1
											"It's OK, but it's going to start raining soon.", // Light = 0 - Rain = 2
											"Hang off a wee bit, weather should be a little better soon."  // Light = 0 - Rain = 3
										) , 
								array (	"It's going to be dark but at least it's not raining.", // Light = 1 - Rain = 0
											"It's going to be dark and raining, you should probably just stay indoors.", // Light = 1 - Rain = 1
											"It's going to be dark and it's going to start raining soon, bad times.", // Light = 1 - Rain = 2
											"It's going to be dark but at least the weather should be clearing up soon."  // Light = 1 - Rain = 3
										) ,
								array (	"Looks OK, but it's going to start getting dark soon.", // Light = 2 - Rain = 0
											"It's raining and it's going to get dark soon, be prepared.", // Light = 2 - Rain = 1
											"It's getting dark and it's probably going to rain soon, bad times.", // Light = 2 - Rain = 2
											"It's going to get dark soon, but at least the weather is going to clear up a little."  // Light = 2 - Rain = 3
										) ,
								array (	"Weathers nice and it should be getting light soon, perfect!", // Light = 3 - Rain = 0
											"Weathers rubbish but at least it's going to get light soon.", // Light = 3 - Rain = 1
											"It's going to be light outside soon, but the weathers going to be rubbish.", // Light = 3 - Rain = 2
											"It's getting light outside soon and the weather should hopefully be clearing up."  // Light = 3 - Rain = 3
										));



?>

