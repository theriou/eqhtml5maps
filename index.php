<?php
// set canvas values
$canvaswidth = '600';
$canvasheight = '600';

// setting default #'s for php to like them numeric
$i = '0';
$maxyline1 = '0';
$maxyline2 = '0';
$minyline1 = '0';
$minyline2 = '0';
$maxxline1 = '0';
$maxxline2 = '0';
$minxline1 = '0';
$minxline2 = '0';

// open file
$handle = fopen('maps/poknowledge.txt', "r");

	// if file exists, go line by line in a loop
	if ($handle) {
    while (($line = fgets($handle)) !== false) {

    // get row data
    $row_data = explode(',', $line);
	
	// adding variables from the data
	$mathyline11 = preg_replace("/[^-0-9.]+/", "", $row_data[0]);
	$mathyline1 = $mathyline11; 
	$mathxline1 = $row_data[1];
	$mathyline2 = $row_data[3];
	$mathxline2 = $row_data[4];
	
	// set the values to the first #'s found
	if ($i == '0') { 
	$maxyline = $mathyline1;
	$maxxline = $mathxline1;
	$minyline = $mathyline1;
	$minxline = $mathxline1;
	}
	
	// 1st loop, get the minY, maxY, minX, maxX
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
	
	// making all values positive to use in the next part
	if ($maxyline < '0') { $lineymax = $maxyline * -1; } else { $lineymax = $maxyline; }
	if ($minyline < '0') { $lineymin = $minyline * -1; } else { $lineymin = $minyline; }
	if ($maxxline < '0') { $linexmax = $maxxline * -1; } else { $linexmax = $maxxline; }
	if ($minxline < '0') { $linexmin = $minxline * -1; } else { $linexmin = $minxline; }
	
	// adding the 2 together to get the max distance between the 2 x points and 2 y points
	$lineytotal = $lineymax + $lineymin;
	$linextotal = $linexmax + $linexmin;
	
	
	// The map in EQ renders to the html5 canvas x,y at large values, in some zones this is in the 2000-3000 range, 
	// So we need to divide all #'s by a value to get it to not require a like 4000 pixel height/width page
	// This should let the page be dynamic to the chosen width and height
	$divnumy = $lineytotal / $canvasheight;
	$divnumx = $linextotal / $canvaswidth;
	
	$i++;
	}
	} else {
    // error opening the file.
	$filefail = '1';
	}
?>
<html>
<style>
body {
    background-image: url("images/cart.png");
    background-repeat: repeat;
}
</style>
<body>
<?php //if file exists, go line by line in a loop
	if ($filefail != '1') {
?>
<canvas id="myCanvas" width="<?php echo $canvaswidth; ?>" height="<?php echo $canvasheight; ?>">
Your browser does not support the canvas element.
</canvas>
<script>
var canvas = document.getElementById("myCanvas");
var ctx = canvas.getContext("2d");
ctx.font = '10px arial';
<?php
	fseek($handle, 0);
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
?>
	ctx.textAlign="center";
	ctx.fillStyle = 'rgb(<?php echo $trcolor; ?>, <?php echo $tgcolor; ?>, <?php echo $tbcolor; ?>)';
	ctx.fillText('<?php echo $textdisplay; ?>', <?php echo ($tyline + $lineymin) / $divnumy; ?>, <?php echo ($row_data[1] + $linexmin) / $divnumx; ?>);
<?php
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
?>
		ctx.beginPath();
		ctx.moveTo(<?php echo $yline1; ?>,<?php echo $xline1; ?>);
		ctx.lineTo(<?php echo $yline2; ?>,<?php echo $xline2; ?>);
		ctx.lineWidth = 1;
		ctx.strokeStyle = 'rgb(<?php echo $rcolor; ?>, <?php echo $gcolor; ?>, <?php echo $bcolor; ?>)';
		ctx.stroke();
	<?php }
	}
	fclose($handle);
?>
</script>
<?php
	} else {
			echo "Map Failed to load or does not exist, try again later or pick a different map.";
			}
?>
</body>
</html>