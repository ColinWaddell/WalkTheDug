<?php

// structured as wtdMessages [ Light Status ] [ Rain Status ]

// Status	0: Good
// 			1: Bad
// 			2: Good->Bad
// 			3: Bad->Good

$wtdMessages = array (  array (	"Looks OK - go walk the dog.", // Light = 0 - Rain = 0
											"It's going to be raining for a wee bit, might as well walk the dog.", // Light = 0 - Rain = 1
											"It's OK, but it's going to start raining soon.", // Light = 0 - Rain = 2
											"Hang off a wee bit, weather should be a little better soon."  // Light = 0 - Rain = 3
										) , 
								array (	"It's dark but at least it's not raining.", // Light = 1 - Rain = 0
											"It's dark and raining, you should probably just stay indoors.", // Light = 1 - Rain = 1
											"It's dark and it's going to start raining soon, bad times.", // Light = 1 - Rain = 2
											"It's dark but at least the weather should be clearing up soon."  // Light = 1 - Rain = 3
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