<?php
	session_start();
	$pagetype='home';
	$subpagetype='queue';
	include "connect_up.php";

	$rootname = 'queue.php';
	include "lhs.html"; ?>

	<div data-id="main" data-role="content">
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

			if ($cmd != '0') {
				echo "<p>about to shell_exec ($cmd)</p>";
				$output = shell_exec($cmd); //." 2>&1 1> /dev/null");
				echo "<p>output was: $output (nothing here means all went well!)</p>";
			}

		?>
		<p>Hmmm. Guess we better put something here that actually works soon...</p>
		<!-- p>All functionality needs access to PI - so this page needs hosting on PI?</p -->
		<ul>
			<li>Function: Remove selected items from queue</li>
			<li><a href="queue.php">View current queue</a>.</li>
			<li><a href="queue.php?queueaction=played">View played items</a>.</li>
<!--
			<li>View current queue - what happens when you arrive here...</li>
			<li><a href="queue.php?queueaction=skip">Skip</a></li>
			<li><a href="queue.php?queueaction=exit">Exit</a></li>
			<li><a href="queue.php?queueaction=play">Play</a></li>
			<li><a href="queue.php?queueaction=reindex">Reindex</a></li>
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
					natcasesort ($files);

					if ($hassome != '0') {
						echo "<div class='queueitem'><ul>\n";
						foreach ($files as $queueitem) {
							echo "<li>".$queueitem."</li>\n";
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
					natcasesort ($files);

					if ($hassome != '0') {
						echo "<div class='queueitem'><ul>\n";
						foreach ($files as $queueitem) {
							echo "<li>".$queueitem."</li>\n";
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
