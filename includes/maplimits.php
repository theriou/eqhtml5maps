<?php 
if (!defined('html5')) { die('Error'); }

function map_limits($filepathini) {

$i = '0';

// open file
$handle = fopen($filepathini, "r");

	// if file exists, go line by line in a loop
    while (($line = fgets($handle)) !== false) {

    // get map row data
    $row_data = explode(',', $line);
	
	// adding variables from the data, only the #'s
	$mathyline11 = preg_replace("/[^-0-9.]+/", "", $row_data[0]);
	$mathyline1 = $mathyline11; 
	$mathxline1 = $row_data[1];
	$mathyline2 = $row_data[3];
	$mathxline2 = $row_data[4];
	
	// set the values to the first #'s found
	if ($i == '0') { 
	$maxyline = $mathyline1;
	$maxxline = $mathxline1;
	$minyline = $mathyline2;
	$minxline = $mathxline2;
	}
	
	// get the minY, maxY, minX, maxX
	if ($mathyline1 > $maxyline) {
		$maxyline = $mathyline1;
	}
	if ($mathyline2 > $maxyline) {
		$maxyline = $mathyline2;
	}
	if ($mathxline1 > $maxxline) {
		$maxxline = $mathxline1;
	}
	if ($mathxline2 > $maxxline) {
		$maxxline = $mathxline2;
	}
	if ($mathyline1 < $minyline) {
		$minyline = $mathyline1;
	}
	if ($mathyline2 < $minyline) {
		$minyline = $mathyline2;
	}
	if ($mathxline1 < $minxline) {
		$minxline = $mathxline1;
	}
	if ($mathxline2 < $minxline) {
		$minxline = $mathxline2;
	}
	
	// making all values positive, canvas can only start at 0,0 it is not Cartesian
	if ($maxyline < '0') { $lineymax = $maxyline * -1; } else { $lineymax = $maxyline; }
	if ($minyline < '0') { $lineymin = $minyline * -1; } else { $lineymin = $minyline; }
	if ($maxxline < '0') { $linexmax = $maxxline * -1; } else { $linexmax = $maxxline; }
	if ($minxline < '0') { $linexmin = $minxline * -1; } else { $linexmin = $minxline; }
	
	// adding the 2 together to get max distance between the 2 x points and 2 y points
	$lineytotal = $lineymax + $lineymin;
	$linextotal = $linexmax + $linexmin;
	
	$i++;
	}
	fclose($handle);
	
	return array($lineytotal, $linextotal, $lineymin, $linexmin, $minyline, $maxyline);
}
?>