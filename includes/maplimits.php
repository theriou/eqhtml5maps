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
	
	// adding the 2 positive numbers together to get max distance
	// all values positive, Canvas can only start at 0,0 it is not Cartesian 
	$lineytotal = (abs($maxyline)) + (abs($minyline));
	$linextotal = (abs($maxxline)) + (abs($minxline));
	
	$i++;
	}
	fclose($handle);
	
	return array($lineytotal, $linextotal, $minyline, (abs($minyline)), (abs($minxline)), $maxyline, (abs($maxyline)));
}
?>