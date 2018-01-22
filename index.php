<?php include_once('includes/maprender.inc');

// set canvas values
$canvaswidth = '600';
$canvasheight = '600';

// if map is not set or it is blank - fail
// otherwise try to get min/max to fit in canvas
if ((!isset($_GET['map'])) OR ($_GET['map'] == '')) {
    $mappicked = '1';
	}

else {

$mapsource1 = strtolower($_GET['map']);
$mapsource = preg_replace("/[^a-z]+/", "", $mapsource1);

$filepath = 'maps/'.$mapsource.'.txt';
$filepath1 = 'maps/'.$mapsource.'_1.txt';
$filepath2 = 'maps/'.$mapsource.'_2.txt';
$filepath3 = 'maps/'.$mapsource.'_3.txt';

if (file_exists($filepath)) {

// setting default #'s for php to like them numeric
$i = '0';

// open file
$handle = fopen($filepath, "r");

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
	$minyline = $mathyline2;
	$minxline = $mathxline2;
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
	}
}
else {
    // file doesn't exist
	$filefail = '1';
} }
?>
<html>
<style>
body {
    background-image: url("images/cart.png");
    background-repeat: repeat;
}
</style>
<body>
<?php 
	// if no map was found in the map GET or it was blank
	// give a list of maps in the /maps folder for now to pick one
	if ($mappicked == '1')
	{
		foreach (glob("maps/*.txt") as $filename) { 
		$filename = str_replace("maps/", "", $filename);
		$filename = str_replace(".txt", "", $filename); ?>
		<a href="maptesting.php?map=<?php echo $filename; ?>"><?php echo $filename; ?></a><?php echo "<br>";
		}
	}
	
	//if the base file existed, progress to rendering it on page
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
	// if base file is found - render it
	if (file_exists($filepath)) {
	map_render($filepath);
	}
	
	// if base_1 file is found - render it
	if (file_exists($filepath1)) {
	map_render($filepath1);
	}
	
	// if base_2 file is found - render it
	if (file_exists($filepath2)) {
	map_render($filepath2);
	}
	
	// if base_3 file is found - render it
	if (file_exists($filepath3)) {
	map_render($filepath3);
	}
?>
</script>
<?php
	} else {
			echo "Map Failed to load or does not exist, try again later or pick a different map.";
			}
?>
</body>
</html>