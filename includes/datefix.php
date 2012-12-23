<?php

function DiffInHours($_date1, $_date2)
{
	
	if (is_string($_date1))
		$_date1 = new DateTime($_date1);
	if (is_string($_date2))
		$_date2 = new DateTime($_date2);
		
	return round(abs($_date1->format('U') - $_date2->format('U')) / (60*60));	
}

?>