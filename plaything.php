<?php
	session_start();
	$pagetype='home';
	$subpagetype='scan';
	include "connect_up.php";

	$rootname = 'plaything.php';
	include "lhs.html"; ?>

	<div id="main">
		<h1>You want to play that thing.</h1>
		<?php
			$band = '0';
			if (isset($_GET['band'])) $band = $_GET['band'];
			if (isset($_POST['band'])) $band = $_POST['band'];
			$bandsort = '0';
			if (isset($_GET['bandsort'])) $bandsort = $_GET['bandsort'];
			if (isset($_POST['bandsort'])) $bandsort = $_POST['bandsort'];
			$album = '0';
			if (isset($_GET['album'])) $album = $_GET['album'];
			if (isset($_POST['album'])) $album = $_POST['album'];
			$track = '0';
			if (isset($_GET['track'])) $track = $_GET['track'];
			if (isset($_POST['track'])) $track = $_POST['track'];

			echo "<!-- INCOMING TRACK: $track -->";
			if ($track != '0') {
				echo "<p>Ok.... I'll Queue the track '$track' from the '$album' album by '$band'.</p>";
				$cmd = $rootshellplayerdir."/track.sh '".$band."' '".$album."' '".$track."'";
			} else {
				echo "<!-- INCOMING ALBUM: $album -->";
				if ($album != '0') {
					echo "<p>Ok.... I'll Queue all the tracks from the '$album' album by '$band'.</p>";
					$cmd = $rootshellplayerdir."/album.sh '".$band."' '".$album."'";
				} else {
					echo "<!-- INCOMING BAND: $band -->";
					echo "<!-- INCOMING BANDSORT: $bandsort -->";
					if ($band != '0') {
						echo "<p>Ok.... I'll Queue all the tracks by '$band' (or '$bandsort'!).</p>";
						$cmd = $rootshellplayerdir."/band.sh '".$band."'";
					} else {
						echo "<p>Confusing lack of incoming parameters?</p>";
					}
				}
			}
			echo "<!-- p>about to shell_exec ($cmd)</p -->";
			$output = shell_exec($cmd." 2>&1 1> /dev/null");
			echo "<p>output was: $output (nothing here means all went well!)</p>";

			$output = shell_exec("ls -l $rootshellplayerdir/queue | wc -l");
			echo "<p>There are now ".($output-1)." files in queue (including me!).</p>";
		?>
		<p>Brilliant. So what now. You want to <a href="scan.php" title="scan another">Scan another</a>?</p>
		<p>Alternatively, just <a onclick="history.go(-1);" title="go back">go back</a>.</p>
	</div>

	<?php include "foot.html"?>
