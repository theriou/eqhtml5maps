<?php 
if (!defined('html5')) { die('Error'); }

function map_limits($filepathini) 
{
	$i = '0';
	$points = '0';

	// open file
	$handle = fopen($filepathini, "r");

	// if file exists, go line by line in a loop
	while (($line = fgets($handle)) !== false) 
	{
		if (empty(trim($line))) 
		{
		}
		else 
		{
    		// get map row data
    		$row_data = explode(',', $line);
	
			// adding variables from the data, only the #'s
			$mathyline1 = preg_replace("/[^-0-9.]+/", "", $row_data[0]);
			$mathxline1 = $row_data[1];
			$mathzline1 = $row_data[2];
	
			if (strpos($row_data[0], 'L') !== false) 
			{
				$points--;
				$mathyline2 = $row_data[3];
				$mathxline2 = $row_data[4];
				$mathzline2 = $row_data[5];
			}
			else
			{
				$points++;
				$mathyline2 = preg_replace("/[^-0-9.]+/", "", $row_data[0]);
				$mathxline2 = $row_data[1];
				$mathzline2 = $row_data[2];
			}
			
			// set the values to the first #'s found
			if ($i == '0') 
			{ 
				$maxyline = $mathyline1;
				$minyline = $mathyline2;
				$maxxline = $mathxline1;
				$minxline = $mathxline2;
				$maxzline = $mathzline1;
				$minzline = $mathzline2;
			}
			
			// get the minY, maxY, minX, maxX, minZ, maxZ
			if ($mathxline1 > $maxxline) 
			{
				$maxxline = $mathxline1;
			}
			if ($mathxline2 > $maxxline) 
			{
				$maxxline = $mathxline2;
			}
			if ($mathxline1 < $minxline) 
			{
				$minxline = $mathxline1;
			}
			if ($mathxline2 < $minxline) 
			{
				$minxline = $mathxline2;
			}
			if ($mathyline1 > $maxyline) 
			{
				$maxyline = $mathyline1;
			}
			if ($mathyline2 > $maxyline) 
			{
				$maxyline = $mathyline2;
			}
			if ($mathyline1 < $minyline) 
			{
				$minyline = $mathyline1;
			}
			if ($mathyline2 < $minyline) 
			{
				$minyline = $mathyline2;
			}
			if ($mathzline1 > $maxzline) 
			{
				$maxzline = $mathzline1;
			}
			if ($mathzline2 > $maxzline) 
			{
				$maxzline = $mathzline2;
			}
			if ($mathzline1 < $minzline) 
			{
				$minzline = $mathzline1;
			}
			if ($mathzline2 < $minzline) {
				$minzline = $mathzline2;
			}

			// adding the 2 positive numbers together to get max distance
			// all values positive, Canvas can only start at 0,0 it is not Cartesian 
			if ($points > 0) 
			{ 
				$maxyline = 0; $maxxline = 0; 
			}
			$lineytotal = (abs($maxyline)) + (abs($minyline));
			$linextotal = (abs($maxxline)) + (abs($minxline));
	
			$i++;
		}
	}
	fclose($handle);
	
	return array($lineytotal, $linextotal, $minyline, $minxline, $maxyline, $maxzline, $minzline);
}
?>