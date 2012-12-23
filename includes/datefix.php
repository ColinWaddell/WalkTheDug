<?php

function DiffInHours($_date1, $_date2)
{
	return round(abs($_date1->format('U') - $_date2->format('U')) / (60*60));	
}

?>