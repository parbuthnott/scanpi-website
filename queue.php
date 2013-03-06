<?php
	session_start();
	$pagetype='home';
	$subpagetype='queue';
	include "connect_up.php";

	$rootname = 'queue.php';
	include "lhs.html"; ?>

	<div id="main">
		<h1>Queue.</h1>

		<?php
			$cmd = '0';
			$queueaction = '0';
			$played='0';
			if (isset($_GET['queueaction'])) $queueaction = $_GET['queueaction'];
			if (isset($_POST['queueaction'])) $queueaction = $_POST['queueaction'];

			echo "<!-- INCOMING QUEUE ACTION: $queueaction -->";
			if ($queueaction == "skip") {
				$cmd = $rootshellplayerdir."/skip.sh";
			}
			if ($queueaction == "exit") {
				$cmd = $rootshellplayerdir."/exit.sh";
			}
			if ($queueaction == "play") {
				$cmd = $rootshellplayerdir."/play.sh";
			}
			if ($queueaction == "reindex") {
				$cmd = $rootshellplayerdir."/reindex.sh";
			}
			if ($queueaction == "played") {
				$played = "true";
			}
			if ($queueaction == "remove") {
				$queueitem = $_GET['queueitem'];
				$cmd = "mv ".$rootshellplayerdir."/queue/".$queueitem." ".$rootshellplayerdir."/played/";
			}
			if ($queueaction == "requeue") {
				$queueitem = $_GET['queueitem'];
				$cmd = "mv ".$rootshellplayerdir."/played/".$queueitem." ".$rootshellplayerdir."/queue/";
				$played = "true";
			}

			if ($cmd != '0') {
				echo "<p>about to shell_exec ($cmd)</p>";
				$output = shell_exec($cmd); //." 2>&1 1> /dev/null");
				echo "<p>output was: $output (nothing here means all went well!)</p>";
			}

		?>

		<!-- p>Hmmm. Guess we better put something here that actually works soon...</p -->
		<!-- p>All functionality needs access to PI - so this page needs hosting on PI?</p -->
		<ul>
			<!-- li>Function: Remove selected items from queue</li -->
			<li><a href="queue.php" title="view current queue">View current queue</a>.</li>
			<li><a href="queue.php?queueaction=played" title="view played items">View played items</a>.</li>
<!--
			<li>View current queue - what happens when you arrive here...</li>
			<li><a href="queue.php?queueaction=skip" title="skip current track">Skip</a></li>
			<li><a href="queue.php?queueaction=exit" title="stop running player">Exit</a></li>
			<li><a href="queue.php?queueaction=play" title="start player">Play</a></li>
			<li><a href="queue.php?queueaction=reindex" title="reindex whole collection">Reindex</a></li>
-->
		</ul>
			<?php

			if ($played == '0') {
				echo "<h2>Queue items...</h2>";
				if (file_exists($rootshellplayerdir."/queue")) {
					echo "<!-- exists: ".$rootshellplayerdir."/queue -->";
					$hassome = '0';
					$queueFolder = opendir($rootshellplayerdir."/queue");
					while (false !== ($file = readdir($queueFolder))) {
						if ($file != "." && $file != "..") {
							$files[] = $file;
							$hassome = '1';
						}
					}
					closedir($queueFolder);

					if ($hassome != '0') {
						natcasesort ($files);
						echo "<div class='queueitem'><ul class='side'>\n";
						foreach ($files as $queueitem) {
							echo "<!-- about to read :".$rootshellplayerdir."/queue/".$queueitem." -->";
							$filedetails = file_get_contents($rootshellplayerdir."/queue/".$queueitem);
							$detailsarray = explode("/", $filedetails);
							echo "<li><a class='queuelink' href=\"queue.php?queueaction=remove&amp;queueitem=".$queueitem."\" title=\"remove item from queue\">x</a> (".$queueitem.")";
							echo " ".$detailsarray[4]." : ".$detailsarray[5]." : ".$detailsarray[6]."</li>\n";
						}
						echo "</ul></div>\n";
					} else {
						echo "<p>Nothing in queue...</p>";
					}

				} else {
					echo "<p>NOT FOUND: ".$rootshellplayerdir."/queue</p>";
				}
			} else {

				echo "<h2>Played items...</h2>";
				if (file_exists($rootshellplayerdir."/played")) {
					echo "<!-- exists: ".$rootshellplayerdir."/played -->";
					$hassome = "false";
					$playedFolder = opendir($rootshellplayerdir."/played");
					while (false !== ($file = readdir($playedFolder))) {
						if ($file != "." && $file != "..") {
							$files[] = $file;
							$hassome = "true";
						}
					}
					closedir($playedFolder);

					if ($hassome != '0') {
						natcasesort ($files);
						echo "<div class='queueitem'><ul class='side'>\n";
						foreach ($files as $queueitem) {
							$filedetails = file_get_contents($rootshellplayerdir."/played/".$queueitem);
							$detailsarray = explode("/", $filedetails);
							echo "<li><a class='queuelink' href=\"queue.php?queueaction=requeue&amp;queueitem=".$queueitem."\" title=\"requeue item\">+</a> (".$queueitem.")";
							echo " ".$detailsarray[4]." : ".$detailsarray[5]." : ".$detailsarray[6]."</li>\n";
						}
						echo "</ul></div>\n";
					} else {
						echo "<p>Nothing in played...</p>";
					}

				} else {
					echo "<p>NOT FOUND: ".$rootshellplayerdir."/played</p>";
				}

			}

			?>
	</div>

	<?php include "foot.html"?>
