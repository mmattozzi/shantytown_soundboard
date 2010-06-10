<html>
<body>
<?php
	$sound = $_GET["sound"];
	if (! $sound) {
		$sound = "033 Zing.mp3";
	}	
	$status = -1;
	print("<div>Playing sound " . $sound . "</div>");
	$cmd = escapeshellcmd("mplayer \"/var/www/$sound\"");
	//$result = `$cmd`;
	//$result = exec($cmd, $status);
	passthru($cmd . " 2>&1");
	print("<div>Status = " . $status . " ... " . $result . "</div>");
?>
</body>
</html>
