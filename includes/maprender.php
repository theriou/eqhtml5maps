<?php 
if (!defined('html5')) { die('Error'); }

function map_render($filepathfull)
{
	global $divnumy, $divnumx, $lineymin, $linexmin, $minyline, $maxyline, $lineymax, $fontsize, $zmin, $zmax, $zaxis;
	
	$handle = fopen($filepathfull, "r");
    while (($line = fgets($handle)) !== false) {
	if (empty(trim($line))) 
	{
	}
	else 
	{
    	// get map row data
    	$row_data = explode(',', $line);
	
		// if the line begins with a P - it is a Point on the map
		// we need to remove the P and just get the # for its coords
		if (strpos($row_data[0], 'P') !== false) 
		{
			if (($zmin > $row_data[2]) OR ($zmax < $row_data[2]))
			{ 
			} 
			else
			{
				$tyline = preg_replace("/[^-0-9.]+/", "", $row_data[0]);

				// text to display on the html5 map, only allow specific characters
				$textdisplay = preg_replace("/[^0-9A-Za-z_()~]+/", "", $row_data[7]);
				$trcolor = preg_replace("/[^0-9.]+/", "", $row_data[3]);
				$tgcolor = preg_replace("/[^0-9.]+/", "", $row_data[4]);
				$tbcolor = preg_replace("/[^0-9.]+/", "", $row_data[5]);
				$rowdatax = ($row_data[1] + $linexmin) / $divnumx;
				$rowdatay = ($tyline + $lineymin) / $divnumy;
				if ($minyline > ($tyline - (strlen($textdisplay) * ($fontsize * 0.6) ))) { echo "ctx.textAlign=\"left\";"; }
				elseif ($maxyline < ($tyline + (strlen($textdisplay) * ($fontsize * 0.6) ))) { echo "ctx.textAlign=\"right\";"; }
				else { echo "ctx.textAlign=\"center\";"; }
				echo "ctx.fillStyle = 'rgb(". $trcolor. ", ".$tgcolor.", ". $tbcolor.")';";
				echo "ctx.fillText('".$textdisplay."', ". $rowdatay .", ". $rowdatax .");";
			}
		} 
		else if (strpos($row_data[0], 'L') !== false) 
		{
			// The text file line starts with an L, so process the Lines
			// then render the lines while this loops through the text file
			// then adding the line mins to push it to start at 0,0 since it is not Cartesian
			if ((($zmin > $row_data[2]) OR ($zmin > $row_data[5])) OR (($zmax < $row_data[2]) OR ($zmax < $row_data[5])))
			{ 
			} 
			else 
			{
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
		else
		{
			//Invalid Line Start, Ignore
		}
	}
	}
	fclose($handle);
}
?>