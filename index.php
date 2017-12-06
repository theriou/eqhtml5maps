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
$divnum = '6';
$handle = fopen('maps/soldungc.txt', "r");

	if ($handle) {
    while (($line = fgets($handle)) !== false) {

    //get row data
    $row_data = explode(',', $line);
	
	if (strpos($row_data[0], 'P') !== false) {
		$tyline = preg_replace("/[^-0-9.]+/", "", $row_data[0]);
		$textdisplay = preg_replace("/[^0-9A-Za-z_()~]+/", "", $row_data[7]);
?>
	ctx.fillText('<?php echo $textdisplay; ?>', <?php echo $tyline / $divnum; ?>, <?php echo $row_data[1] / $divnum;?>);
	<?php
    } else { 
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
