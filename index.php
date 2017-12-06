<html>
<style>
body {
    background-image: url("images/cart.png");
    background-repeat: repeat;
}
</style>
<body>
<canvas id="myCanvas" width="800" height="1200">
Your browser does not support the canvas element.
</canvas>
<script>
var canvas = document.getElementById("myCanvas");
var ctx = canvas.getContext("2d");
ctx.translate(300, 200);
ctx.font = '14px arial';
<?php
//The map in EQ renders to the html5 canvas x,y at large values, in some zones this is in the 2000-3000 range, 
//So we need to divide all #'s by a value to get it to not require a like 4000 pixel height/width page
$divnum = '6';

//open file
$handle = fopen('maps/soldungc.txt', "r");

	//if file exists, go line by line in a loop
	if ($handle) {
    while (($line = fgets($handle)) !== false) {

    //get row data
    $row_data = explode(',', $line);
	
	//if the line begins with a P - it is a Point on the map, we need to remove the P and just get the #
	if (strpos($row_data[0], 'P') !== false) {
		$tyline = preg_replace("/[^-0-9.]+/", "", $row_data[0]);
		
		//text to display on the html5 map, only allow specific characters
		$textdisplay = preg_replace("/[^0-9A-Za-z_()~]+/", "", $row_data[7]);
?>
	ctx.fillText('<?php echo $textdisplay; ?>', <?php echo $tyline / $divnum; ?>, <?php echo $row_data[1] / $divnum;?>);
	<?php
    } else { 
	//The text file line didn't start with a P, so we are processing the Lines and dividing them by divnum
	// then we will render the lines while this loops through the text file
	$yline11 = preg_replace("/[^-0-9.]+/", "", $row_data[0]);
	$yline1 = $yline11 * 1 / $divnum; 
	$xline1 = $row_data[1] * 1 / $divnum;
	$yline2 = $row_data[3] * 1 / $divnum;
	$xline2 = $row_data[4] * 1 / $divnum;
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
} else {
    // error opening the file.
       }
?>
</script>
</body>
</html>
