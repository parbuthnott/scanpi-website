<?php

	$vartoday = date("dS F Y");

	// testing on scanpi.co.uk
	$rootpiurl = $_SERVER['SERVER_NAME']."/scanpi";
	$rootscanurl = "zxing://scan/?ret=";
	$rootscanreturnurl = URLencode($rootpiurl."/bar2brainz2tracks.php?barcode={CODE}");
	$roototherscanurl = "pic2shop://scan?callback=";
	$roototherscanreturnurl = URLencode($rootpiurl."/bar2brainz2tracks.php");
	$rootmbrainzurl = "http://www.musicbrainz.org/ws/2/release/?query=barcode:";
	$rootmusicdir = "./music";
	$rootshellplayerdir = "./shellplayer";

	// final
	// $rootpi = $_SERVER['SERVER_NAME'];
	// $rootpiurl = "http://".$rootpi."/scanpi";
	// $rootscanurl = "zxing://scan/?ret=";
	// $rootscanreturnurl = URLencode($rootpiurl."/bar2brainz2tracks.php?barcode={CODE}");
	// $roototherscanurl = "pic2shop://scan?callback=";
	// $roototherscanreturnurl = URLencode($rootpiurl."/bar2brainz2tracks.php");
	// $rootmbrainzurl = "http://www.musicbrainz.org/ws/2/release/?query=barcode:";
	// $rootmusicdir = "/mnt/2tb_USB_hard_disc/p_music";
	// $rootshellplayerdir = "/mnt/scanpi/shellplayer";

	function showBrowseItem ($sBand, $sAlbum) {
		// SHOULD NOT NEED THIS??
		// $rootpi = $_SERVER['SERVER_NAME'];
		$rootpi = $_SERVER['SERVER_NAME']."/scanpi";
		// $rootmusicdir = "/mnt/2tb_USB_hard_disc/p_music";
		$rootmusicdir = "./music";
		$coverimage = "no_cover.jpg";
		if (file_exists($rootmusicdir."/".$sBand."/".$sAlbum)) {
			echo "<!-- exists: ".$rootmusicdir."/".$sBand."/".$sAlbum." -->";
			$albumFolder = opendir($rootmusicdir."/".$sBand."/".$sAlbum);
			while (false !== ($file = readdir($albumFolder))) {
				if ($file != "." && $file != ".." && preg_match('/mp3/i', $file)) {
					$files[] = $file;
				}
				if ($file != "." && $file != ".." && preg_match('/Folder\.jpg/i', $file)) {
					$coverimage = $file;
				}
			}
			closedir($albumFolder);
			natcasesort ($files);

			echo "<div class='browseitem'>\n";

			if ($coverimage != "no_cover.jpg") {
				echo "<div class='albumcover'><img src='".$rootmusicdir."/".$sBand."/".$sAlbum."/".$coverimage."' alt='$sBand $sAlbum' title='$sBand $sAlbum' /></div>\n";
			} else {
				echo "<div class='albumcover'><img src='$coverimage' alt='$sBand $sAlbum' title='$sBand $sAlbum' /></div>\n";
			}
			echo "<div class='tracklist'>";
			echo "<div class='albumtitle'><a href=\"plaything.php?album=".urlencode($sAlbum)."&amp;band=".urlencode($sBand)."\" title=\"queue this album\">Queue Album: $sAlbum</a>.</div>\n";
			echo "<div class='band'><a href=\"plaything.php?band=".urlencode($sBand)."\" title=\"queue all albums\">Queue All by $sBand</a>.</div>\n";
			echo "<ul class='side'>";
			$urlsBand = str_replace(" ", "%20", $sBand);
			$urlsAlbum = str_replace(" ", "%20", $sAlbum);
			// echo "<!-- from ".$sBand." to ".$urlsBand." and from ".$sAlbum." to ".$urlsAlbum." -->";
			foreach ($files as $track) {
				$urltrack = str_replace(" ", "%20", $track);
				echo "<li>";
	// DO THIS BY CURRENT DOMAIN? echo "<a class=\"playlink\" href=\"http://192.168.1.227/mnt/2tb_USB_hard_disc/p_music/".$urlsBand."/".$urlsAlbum."/".$urltrack."\">&gt;</a>\n";
				echo "<a class=\"playlink\" href=\"http://".$rootpi."/mnt/2tb_USB_hard_disc/p_music/".$urlsBand."/".$urlsAlbum."/".$urltrack."\" title=\"play track on this device\">&gt;</a>\n";
				echo "<a class=\"queuelink\" href=\"plaything.php?track=".urlencode($track)."&amp;album=".urlencode($sAlbum)."&amp;band=".urlencode($sBand)."\" title=\"add track to scanpi's queue\">+</a>\n";
				echo "$track</li>\n";
			}
			echo "</ul></div>\n";

			echo "</div>\n";
		} else {
			echo "<p>NOT FOUND: ".$rootmusicdir."/".$sBand."/".$sAlbum."</p>";
		}
		return;
	}

?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' lang='en' xml:lang='en'>
<head>
	<meta name="author" content="Peter Arbuthnott for SCANPI"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta http-equiv='Content-type' content='text/html;charset=UTF-8' />
	<title>SCANPI Queue Manager</title>
	<link rel='stylesheet' href='scanpi.css' type='text/css' />
	<!-- meta name="google-site-verification" content="WHu2Fe6emfkVVrsFH6Bg0TuKOP9CJD-Zn1M-ucse1Y0" / -->
	<link rel="shortcut icon" href="favicon.ico" />
</head>
<body>
<!-- <?php echo "rootpi: $rootpi"; ?> -->
