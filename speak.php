<html> 
	<head> 
		<title>Shantytown Soundboard</title>

		<style type="text/css">
			#upload.hover {
				text-decoration:underline;
			}	
		</style>

		<script type="text/javascript" src="javascript/prototype.js"></script> 
		<script type="text/javascript" src="javascript/ajaxupload.js"></script>
		<script type="text/javascript"> 
	
			function init() {	
				new AjaxUpload("upload", 
					{action: 'uploadSound.php', onComplete: uploadComplete });
			}
	
			function playSound(id) {
				var retVal = new Object();
				var file = document.getElementById(id).value;
				if (document.getElementById("playlocal").checked) {				
					var audio = document.createElement("audio");
					audio.src = "sounds/" + file;				
					audio.play();
					retVal.local = true;
				}
				if (document.getElementById("playremote").checked) {				
					time = new Date().getTime();
					new Ajax.Request('play.php', { method:'get', parameters: { time: time, sound: "sounds/" + file } });
					retVal.remote = true; 
				}
				return retVal;
			}

			function uploadComplete(file, response) {
				alert("Upload complete, response = " + response);
				window.location.reload();
			}
			
		</script> 
	</head>

	<body onload="init();">
		<div>
		<?php
			$farray = array();
			if ($dh = opendir("/var/www/sounds")) {
				while (($file = readdir($dh)) !== false) {
					if (strstr($file, "mp3") || strstr($file, "wav")) {
						array_push($farray, $file);	
					}
				}
				closedir($dh);
			} else {
				print("Couldn't open directory");
			}

			sort($farray);
			for ($i = 0; $i < sizeof($farray); $i++) {
				$file = $farray[$i];
				$str2 = str_replace("'", "", $file);
				print("<span><button id=\"" . $str2 . "\" onclick=\"playSound('" . $str2 . "');\" value=\"" . $file . "\">$file</button></span>\n");
			}

		?>
		</div>
		<div style="padding: 12px;" >
			<input type="checkbox" id="playlocal" checked="true"/>Play Locally 
			<input type="checkbox" id="playremote"/>Play Remote 
		</div>
		<div>
			<a href='#' id="upload">Upload a new sound!</a>	
		</div> 
	</body>
</html>
