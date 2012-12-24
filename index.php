
<!doctype html>
<!--[if lt IE 9]><html class="ie"><![endif]-->
<!--[if gte IE 9]><!--><html><!--<![endif]-->
	
	<head>
		<meta charset="utf-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		
		<title>Walk the dug?</title>
		
		<script src="http://code.jquery.com/jquery-latest.js"></script>
		<script src="js/wtd.js"></script>
		<script src="js/jquery.cookie.js"></script>
		
		<script type="text/javascript" src="js/jquery.jqplot.min.js"></script>
		<script type="text/javascript" src="js/jqplot.dateAxisRenderer.min.js"></script>

		<link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700,300italic|Averia+Serif+Libre:400italic' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="css/wtd.css" type="text/css" media="all" />
		
		
		<!--[if lt IE 9]>
			<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
	</head>
	
	<body lang="en">
		<div class="row main-body">
				<div class="column grid_6">
					
				<header id="title">
					<div class="row">
						<div class="column grid_6">
							<h1>Walk the dug?</h1>
						</div>

					</div>
				</header>
	
				<div id="results">
				
					<section id="location">
						<div class="row">
							<div class="column grid_4">
								<p class="align-right"><a href="#" id="user-location" class="show-editor">checking</a></p>
							</div>
							<div class="column grid_1 align-center">
								<a class="show-editor" href="#"><img src="img/change.png" width="64" height="64" alt="find location"></a>
							</div>
							<div class="column grid_1 align-center">
								<img src="img/dog.png" alt="walk the dug">
							</div>
						</div>
					</section>
				
					<section id="message" class="italic">
						<p>Loading weather data</p>
					</section>
		
					<section id="why">
						<div class="row">
							<div class="column grid_6">
									<h2 class="align-right italic"><a href="#" id="why-link">more info &darr;</a></h2>
							</div>
						</div>

						<div id="why-data">
							<div class="row">
								<div class="column grid_2" id="astro_data">
									<div class="row">
										<div class="column grid_2">
											<br />
											<h3>The conditions are based off of the time of day, and the chance of rain</h3>
										</div>
									</div>
									<div class="row">
										<br />
										<div class="column grid_1">
											<p>Time Now</p>		
										</div>
										<div class="column grid_1">
											<p>- <span id="current_time"></span></p>
										</div>
									</div>
									<div class="row">
										<div class="column grid_1">
											<p>Sunset</p>		
										</div>
										<div class="column grid_1">
											<p>- <span id="sunset_time"></span></p>
										</div>
									</div>
									<div class="row">
										<div class="column grid_1">
											<p>Sunrise</p>	
										</div>
										<div class="column grid_1">
											<p>- <span id="sunrise_time"></span></p>
										</div>
									</div>
								</div>

								<div class="column grid_4">
									<div id="raingraph" class="graph">
									</div>								
								</div>
							</div>
							
						</div>

					</section>
									
				</div> <!-- results -->
			
				<section id="location-editor">

					   <label>Enter your location and select from the list</label>
							<br />
						<input type="text" id="location-text" class="grid_5 italic"> <button type="button" id="clear-button">Clear</button>
							<br />
						<select id="location-list" size="5" class="grid_5">
						</select>
							<br />
						<button type="button" id="update-button">Update</button>
						<button type="button" id="hide-button">Hide</button>

				</section> <!-- location editor -->
		
				<footer>
						<div class="row">
							<div class="column grid_3">
								<p>Walk the dug was made by <a href="http://colinwaddell.com">ColinWaddell.com</a></p>
								<p>Icons from the <a href="http://thenounproject.com/">noun project</a></p>
							</div>
							<div class="column grid_3 align-right">
								<p>The source code can be found <a href="https://github.com/ColinWaddell/WalkThedug">here</a><p>
								<p>Weather data from <a href="http://wunderground.com/weather/api/">Wunderground</a></p>
							</div>
						</div>
					</div>
					
					
				</footer>
			</div>
		</div> <!-- wrapper -->
		
		
		<script>
			var _gaq=[['_setAccount','UA-13023652-3'],['_trackPageview']];
			(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
			g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
			s.parentNode.insertBefore(g,s)}(document,'script'));
		</script>
		
	</body>
	
</html>