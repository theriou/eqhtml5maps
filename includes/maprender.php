<?php
function map_render($filepathfull)
	{
		global $divnumy, $divnumx, $lineymin, $linexmin;
	
	$handle = fopen($filepathfull, "r");
    while (($line = fgets($handle)) !== false) {

    //get row data
    $row_data = explode(',', $line);
	
	//if the line begins with a P - it is a Point on the map, we need to remove the P and just get the #
	if (strpos($row_data[0], 'P') !== false) {
		$tyline = preg_replace("/[^-0-9.]+/", "", $row_data[0]);
		
		//text to display on the html5 map, only allow specific characters
		$textdisplay = preg_replace("/[^0-9A-Za-z_()~]+/", "", $row_data[7]);
		$trcolor = preg_replace("/[^0-9.]+/", "", $row_data[3]);
		$tgcolor = preg_replace("/[^0-9.]+/", "", $row_data[4]);
		$tbcolor = preg_replace("/[^0-9.]+/", "", $row_data[5]);
		echo "ctx.textAlign=\"center\";";
		echo "ctx.fillStyle = 'rgb(". $trcolor. ", ".$tgcolor.", ". $tbcolor.")';";
		echo "ctx.fillText('".$textdisplay."', ".(($tyline + $lineymin) / $divnumy).", ".(($row_data[1] + $linexmin) / $divnumx).");";
		
    } else {
	// The text file line didn't start with a P, so we are processing the Lines and dividing them by divnum
	// then we will render the lines while this loops through the text file
	// also adding the line minimums to push it into Canvas's 0,0 start system
	$yline11 = preg_replace("/[^-0-9.]+/", "", $row_data[0]);
	$yline1 = ($yline11 + $lineymin) / $divnumy;
	$xline1 = ($row_data[1] + $linexmin) / $divnumx;
	$yline2 = ($row_data[3] + $lineymin) / $divnumy;
	$xline2 = ($row_data[4] + $linexmin) / $divnumx;
	$rcolor = preg_replace("/[^0-9.]+/", "", $row_data[6]);
	$gcolor = preg_replace("/[^0-9.]+/", "", $row_data[7]);
	$bcolor = preg_replace("/[^0-9.]+/", "", $row_data[8]);
		echo "ctx.beginPath();";
		echo "ctx.moveTo(".$yline1.",".$xline1.");";
		echo "ctx.lineTo(".$yline2.",".$xline2.");";
		echo "ctx.lineWidth = 1;";
		echo "ctx.strokeStyle = 'rgb(".$rcolor.", ".$gcolor.", ".$bcolor.")';";
		echo "ctx.stroke();";
	}
	}
	fclose($handle);
	}
?>