<?php define('html5', 'map');
require_once('includes/maprender.php');
require_once('includes/maplimits.php');

// Set canvas max width and font size/type, height will be dynamic to keep Aspect Ratio intact
// Set if you want Z Axis control to 1 instead of 0, this lets you edit Z Axis and canvas width on page as Form
$canvaswidth = '800';
$fontsize = '10';
$fonttype = 'Arial';
$zaxis = '0';

// Stuff past this point shouldn't need to be touched
// Unless you want to Embed it in a page


// If map is not set or it is blank - Show Pick Map options
// If it is set continue to rendering and finding map bounds
if ((!isset($_GET['map'])) OR ($_GET['map'] == '')) {
    $mappicked = '0';
}
else {

if (isset($_GET['cwidth']))
{ 
	$cwidth = preg_replace("/[^0-9]+/", "", $_GET['cwidth']);
		if ($cwidth == '') 
		{ 
			$canvaswidth = $canvaswidth; 
		}
		else
		{
			$canvaswidth = $cwidth;
		}
}
else
{
}

$zmin = '-99999999999999999'; $zmax = '99999999999999999';
if ((isset($_GET['zmin'])) OR (isset($_GET['zmax'])))
{
	if (isset($_GET['zmin'])) {
		$zmin = preg_replace("/[^0-9]+/", "", $_GET['zmin']); if ($zmin == '') { $zmin = '-99999999999999999'; }
	}
	if (isset($_GET['zmax'])) {
		$zmax = preg_replace("/[^0-9]+/", "", $_GET['zmax']); if ($zmax == '') { $zmax = '99999999999999999'; }
	}
}

$mapsource1 = strtolower($_GET['map']);
$mapsource = preg_replace("/[^a-z0-9]+/", "", $mapsource1);

$filepath = 'maps/'.$mapsource.'.txt';
$filepath1 = 'maps/'.$mapsource.'_1.txt';
$filepath2 = 'maps/'.$mapsource.'_2.txt';
$filepath3 = 'maps/'.$mapsource.'_3.txt';

// Setting some default values in case some map files aren't found
$lineytotal1 = 0; $lineytotal2 = 0; $lineytotal3 = 0; $lineytotal4 = 0; 
$linextotal1 = 0; $linextotal2 = 0; $linextotal3 = 0; $linextotal4 = 0; 
$minyline1 = 0; $minyline2 = 0; $minyline3 = 0; $minyline4 = 0; 
$linexmin1 = 0; $linexmin2 = 0; $linexmin3 = 0; $linexmin4 = 0; 
$maxyline1 = 0; $maxyline2 = 0; $maxyline3 = 0; $maxyline4 = 0;
$maxzline1 = 0; $maxzline2 = 0; $maxzline3 = 0; $maxzline4 = 0;
$minzline1 = 0; $minzline2 = 0; $minzline3 = 0; $minzline4 = 0;

// Checking for Map Files, Then checking if they are > 0 bytes
// If both succeed, get the potential min and max X and Y values
// If both fail, set fail status
if ((file_exists($filepath)) OR (file_exists($filepath1)) OR (file_exists($filepath2)) OR (file_exists($filepath3))) {
	if ((filesize($filepath) > '0') OR (filesize($filepath1) > '0') OR (filesize($filepath2) > '0') OR (filesize($filepath3) > '0')) {
		if (file_exists($filepath)) { 
			if (filesize($filepath) > '0') {
				list($lineytotal1, $linextotal1, $minyline1, $linexmin1, $maxyline1, $maxzline1, $minzline1) = map_limits($filepath);
			}
		}
		if (file_exists($filepath1)) {
			if (filesize($filepath1) > '0') { 
				list($lineytotal2, $linextotal2, $minyline2, $linexmin2, $maxyline2, $maxzline2, $minzline2) = map_limits($filepath1);
			}
		}
		if (file_exists($filepath2)) { 
			if (filesize($filepath2) > '0') { 
				list($lineytotal3, $linextotal3, $minyline3, $linexmin3, $maxyline3, $maxzline3, $minzline3) = map_limits($filepath2);
			}
		}
		if (file_exists($filepath3)) { 
			if (filesize($filepath3) > '0') { 
				list($lineytotal4, $linextotal4, $minyline4, $linexmin4, $maxyline4, $maxzline4, $minzline4) = map_limits($filepath3);
			}
		}
	}
	else
	{
		$filefail = '1'; 
	}
}
else 
{ 
	$filefail = '1'; 
}

// Getting the max values from every map file found to be used in the rendering later on
// This should let any actual map render properly no matter which file its in
// This is mostly a thing with older ones
$linextotal = max($linextotal1, $linextotal2, $linextotal3, $linextotal4);
$lineytotal = max($lineytotal1, $lineytotal2, $lineytotal3, $lineytotal4);
$linexmin = max($linexmin1, $linexmin2, $linexmin3, $linexmin4);
$maxyline = max($maxyline1, $maxyline2, $maxyline3, $maxyline4);
$lineymax = abs($maxyline);
$minyline = min($minyline1, $minyline2, $minyline3, $minyline4);
$lineymin = abs($minyline);
$linezmax = max($maxzline1, $maxzline2, $maxzline3, $maxzline4);
$linezmin = min($minzline1, $minzline2, $minzline3, $minzline4);


// The map in EQ renders to the html5 canvas x,y at large values by default
// In some zones this is can be in the 2000-3000 range
// So we need to divide all #'s to not require a like 4000 pixel height/width page
// This should keep the Aspect Ratio intact while +/- the Height dynamically
$divnumy = $lineytotal / $canvaswidth;
$divnumx = $divnumy;
$canvasheight = (($linextotal / $lineytotal) * $canvaswidth) + 5;

}
?>
<html>
<head>
<title><?php if ($mapsource) { echo "Map of " . $mapsource; } else { echo "Choose a Map"; } ?></title>
<style>
body {
    background-image: url("images/cart.png");
    background-repeat: repeat;
}
</style>
</head>
<body>
<?php 
	// if no map was found in the map GET or it was blank
	// give a list of maps in the /maps folder to pick one
	if ($mappicked == '0')
	{
		foreach (glob("maps/*.txt") as $filename) { 
		$filename = str_replace("maps/", "", $filename);
		$filename = str_replace(".txt", "", $filename);
			if (stripos($filename, "_") !== false) {
				// ignore files with _ in them and only list the base file versions
			} else {
				?><a href="maptesting.php?map=<?php echo $filename; ?>"><?php echo $filename; ?></a><?php echo "<br>";
			}
		}
	}
	
	// if the base file existed, continue to render
	if ($filefail != '1') {
		
	if ($zaxis == '1') {
		echo "<br>Min Z: " . $linezmin . " - Max Z: " . $linezmax . "<br><br>";
?>
	<form action="maptesting.php" method="GET">
	Min Z <textarea rows="1" cols="10" name="zmin"><?php if (isset($_GET['zmin'])) { echo $_GET['zmin']; } else { } ?></textarea> - 
	Max Z <textarea rows="1" cols="10" name="zmax"><?php if (isset($_GET['zmax'])) { echo $_GET['zmax']; } else { } ?></textarea> -
	Canvas Width <textarea rows="1" cols="10" name="cwidth"><?php if (isset($_GET['cwidth'])) { echo $_GET['cwidth']; } else { } ?></textarea>
	<input name="map" type="hidden" id="map" value="<?php echo $mapsource; ?>">
	<input type="Submit" value="Submit" name="Submit">
	</form>
	<br><br>
<?php
	} 
	else 
	{ 
	}
?>
<canvas id="myCanvas" width="<?php echo $canvaswidth; ?>" height="<?php echo $canvasheight; ?>">
Your browser does not support the canvas element.
</canvas>
<script>
var canvas = document.getElementById("myCanvas");
var ctx = canvas.getContext("2d");
ctx.font = '<?php echo $fontsize; ?>px <?php echo $fonttype; ?>';
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
	} 
	else 
	{
		echo "Map Failed to load or doesn't exist in a valid format. Try again later or pick a different map.";
	}
?>
</body>
</html>