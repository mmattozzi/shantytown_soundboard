<html> 
	<head> 
		<title>Shantytown Soundboard</title>

		<style type="text/css">
			#upload.hover {
				text-decoration:underline;
			}	
		</style>
	
		<link type="text/css" href="javascript/css/smoothness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
		<script type="text/javascript" src="javascript/js/jquery-1.4.2.min.js"></script>	
		<script type="text/javascript" src="javascript/js/jquery-ui-1.8.2.custom.min.js"></script>
		<script type="text/javascript" src="javascript/ajaxupload.js"></script>
		<script type="text/javascript"> 
	
			var ia = [];

			$(document).ready(function() {
				new AjaxUpload("upload", 
					{action: 'uploadSound.php', onComplete: uploadComplete });

				enableTypeahead("typeahead", "button");
			});
	
			function enableTypeahead(typeaheadField, buttonClass) {
				var buttons = $("." + buttonClass);
				var idArray = [];
				var titleArray = [];
				var visible = [];				
				for (buttonId in buttons) {
					if (buttons[buttonId].id) {
						idArray.push(buttons[buttonId].id);
						titleArray.push(buttons[buttonId].value);
					}
				}

				ia = idArray;				

				$("#" + typeaheadField).keyup( function(event) {
					if (event.keyCode == 13) {
						if (visible.length == 1) {
							visible[0].click();
						}	
					} else {
						visible = [];
						var text = $(this).val();
						var re = new RegExp(text, "i");
						for (i = 0; i < titleArray.length; i++) {
							if ( titleArray[i].search(re) < 0 ) {
								if (idArray[i] == "Chargemp3") { alert("hiding charge"); }
								$("#" + idArray[i] ).hide();
							} else {
								$("#" + idArray[i] ).show();
								visible.push($("#" + idArray[i])[0]);
							}
						}
					}
				});
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
					$.get('play.php', { time: time, sound: "sounds/" + file } );					
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

	<body>
		<div>
		<?php
			$script_directory = dirname(__FILE__);
	
			$farray = array();
			if ($dh = opendir($script_directory . "/sounds")) {
				while (($file = readdir($dh)) !== false) {
					if (strstr($file, "mp3") || strstr($file, "wav")) {
						array_push($farray, $file);	
					}
				}
				closedir($dh);
			} else {
				print("Couldn't open directory: " . $script_directory);
			}

			sort($farray);
			$patterns = array();
			$patterns[0] = "'";
			$patterns[1] = ".mp3";
			$patterns[2] = ".wav";
			$patterns[3] = " ";
			$patterns[4] = ".";
			$patterns[5] = ",";
			$patterns[6] = "(";
			$patterns[7] = ")";
			for ($i = 0; $i < sizeof($farray); $i++) {
				$file = $farray[$i];
				$str2 = str_replace($patterns, "", $file);
				print("<span><button class=\"button\" id=\"" . $str2 . "\" onclick=\"playSound('" . $str2 . "');\" value=\"" . $file . "\">$file</button></span>\n");
			}

		?>
		</div>
		Filter: <input id="typeahead" type="text"/>
		<div style="padding: 12px;" >
			<input type="checkbox" id="playlocal" checked="true"/>Play Locally 
			<input type="checkbox" id="playremote"/>Play Remote 
		</div>
		<div>
			<a href='#' id="upload">Upload a new sound!</a>	
		</div> 
	</body>
</html>
