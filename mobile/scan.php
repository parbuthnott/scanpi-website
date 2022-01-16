<?php
	session_start();
	$pagetype='home';
	$subpagetype='scan';
	include "connect_up.php";

	$rootname = 'scan.php';
	include "lhs.html"; ?>

	<div data-id="main" data-role="content">
		<h1>Scan.</h1>
		<?php

			$ua = $_SERVER['HTTP_USER_AGENT'];
			$checker = array(
				'iphone'=>preg_match('/iPhone|iPod|iPad/', $ua),
				'blackberry'=>preg_match('/BlackBerry/', $ua),
				'android'=>preg_match('/Android/', $ua),
			);

			if ($checker['iphone']) {
				// do something for iPhone
				echo "<p>iPhone detected...</p>";
				echo "<p><a class='mainscan' href='#' onclick=\"javascript:iPhoneToURL('".$roototherscanurl.$roototherscanreturnurl."');\">SCAN [pic2shop]</a></p>";
				// echo "<p><a class='mainscan' href='#' onclick=\"javascript:iPhoneToURL('".$rootscanurl.$rootscanreturnurl."');\">SCAN [zxing]</a></p>";

			} elseif ($checker['android']) {
				// do something for Android
				echo "<p>Android detected...</p>";
				echo "<p><a class='mainscan' href='#' onclick=\"javascript:androidToURL('".$roototherscanurl.$roototherscanreturnurl."');\">SCAN [pic2shop]</a></p>";
				// echo "<p><a class='mainscan' href='#' onclick=\"javascript:androidToURL('".$rootscanurl.$rootscanreturnurl."');\">SCAN [zxing]</a></p>";

			} else {
				// do something for Windows / Blackberry / other?
				echo "<p>Neither iPhone nor Android detected...</p>";
				echo "<p><a class='mainscan' href='".$roototherscanurl.$roototherscanreturnurl."'>SCAN [pic2shop]</a></p>";
				echo "<p><a class='mainscan' href='".$rootscanurl.$rootscanreturnurl."'>SCAN [zxing]</a></p>";

			}

/*
			echo "<a href='http://zxing.appspot.com/scan?ret=http%3A%2F%2Fwww.scanpi.co.uk%2Fscanpi%2Fbar2brainz2tracks.php%3Fbarcode%3D%7BCODE%7D'>SCAN</a><br />";
			echo "<a href='http://zxing.appspot.com/scan?ret=http%3A%2F%2Fwww.scanpi.co.uk%2Fscanpi%2Fbar2brainz2tracks.php%3Fbarcode%3D%7BCODE%7D%2Fdescription&SCAN_FORMATS=UPC_A,EAN_13'>SCAN</a></br />";
			echo "<p><a class='mainscan' href='".$roototherscanurl.$roototherscanreturnurl."'>SCAN [pic2shop]</a></p>";
			echo "<p><a class='mainscan' href='".$rootscanurl.$rootscanreturnurl."'>SCAN [zxing]</a></p>";
			echo "<p><a href='$rootpiurl/bar2brainz2tracks.php?barcode=731455003420'>test ABBA - Waterloo / 731455003420</a></p>";
			echo "<p><a href='$rootpiurl/bar2brainz2tracks.php?barcode=5018766962482'>test Pavement - Brighten the corners / 5018766962482</a></p>";
			echo "<p><a href='$rootpiurl/bar2brainz2tracks.php?barcode=scanpi://Tapes/Stone Roses Plus'>test scanpi://Tapes/Stone Roses Plus</a></p>";
			echo "<p><a href='$rootpiurl/bar2brainz2tracks.php?barcode=scanpi://Boogie Down Productions/By All Means Necessary'>test scanpi://Boogie Down Productions/By All Means Necessary</a></p>";
*/
			// #option# perhaps point the return to the PI?
			// #option# link should fire up scanning app, but ... test test test
			// #option# wrap scanner into PHP / HTML5 call / ???
		?>
	</div>

	<?php include "foot.html"?>
