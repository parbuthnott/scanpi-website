<?php
    session_start();
	$pagetype='home';
	$subpagetype='playem';
	include "connect_up.php";

//make and play dirs worth of music
if (isset($_GET['guest'])) {
	$guestname = $_GET['guest'];
	$rootpi = $_SERVER['SERVER_NAME'];

	print "<p> current user file : <a href=\"guestlists/".$guestname.".m3u\" title=\"download the playlist(M3U) file\">guestlists/".$guestname.".m3u</a><br/>\n";
//	if ($guestname == "peter.arbuthnott") {
//		print "<a href=\"playem.php?guest=".$guestname."&amp;edit=true\">edit this guestlist</a><br/>\n";
//		print "<a href=\"playem.php?guest=".$guestname."&amp;edit=true\">do whole dir</a><br/>\n";
//	}

	print "<a href=\"playem.php?guest=".$guestname."&amp;guestlists=true\" title=\"view all guestlist files\">show guestlists</a><br/>\n";
	print "<a href=\"playem.php?guest=".$guestname."\" title=\"back to top directory\">back to top dir</a></p></div><div id='mainpage'>\n";

	if (isset($_GET['guestlists'])) {
		//show guest links
		$headFolder = opendir("guestlists");
		while (false !== ($file = readdir($headFolder))) {
			if ($file != "." && $file != ".." && !preg_match('/~/i',$file)) {
				$files[] = $file;
			}
		}
		closedir($headFolder);
		if ($files) natcasesort($files);
		echo "<div id='guestlists'>Other guest with playlists...  clickey to openup m3u<br/>\n";
		foreach ($files as $file) {
			if (preg_match('/m3u/i',$file)) {
				echo "<a href=\"guestlists/".$file."\" title=\"download the playlist(M3u) file ".$file."\">".$file."</a><br/>\n";
			}
		}
		echo "</div>";

	} else {

		if (isset($_GET['file'])) {
			//show file details
			$filename = $_GET['file'];
			$currentdir = html_entity_decode($_GET['dir']);
			echo "<div id='playem'>";
			echo "File now adding to playlist :: $guestname.m3u <br/>";
			echo "</div>\n";

			if (isset ($_GET['guest'])) {
				//if guest chosen, add to file
				echo "<div id='message'>";
				$filetoappend = "guestlists/".$guestname.".m3u";
		  		if (!file_exists($filetoappend)) touch($filetoappend);
				if ($handle = fopen($filetoappend, 'a')) {
					$realname = preg_replace('/.*\/(.*?)/i', '$2', $filename);
					// remove the extra '/' and swap spaces out
					$messedfile = substr(str_replace(' ', '%20', $currentdir)."/".rawurlencode($realname),1);
					$messedfile = str_replace('+', '%2b', $messedfile);
					$messedfile = str_replace('&', '%26', $messedfile);

					// if ($currentdir != ".") {
					//	$messedfile = substr($currentdir."/".rawurlencode($realname), 2);
					// } else {
					// 	$messedfile = rawurlencode($realname);
					// }
					if (fwrite($handle, "#EXTINF:123,".$realname."\n".
//						"http://www.peachjuice.net/music/".$messedfile."\n") === FALSE) {
//						"http://82.24.252.201/disc2music/".$messedfile."\n") === FALSE) {
//						"http://192.168.1.227/".$messedfile."\n") === FALSE) {
						"http://".$rootpi."/".$messedfile."\n") === FALSE) {
						echo "cannot write to file $filetoappend";
					} else {
						echo "<br/><br/>*** WORK HAS BEEN DONE! ***<br/>appended ".$filename." to ".$filetoappend."<br/><br/>\n";
						$currentdir = str_replace(' ', '%20', $currentdir);
						$currentdir = str_replace('+', '%2b', $currentdir);
						$currentdir = str_replace('&', '%26', $currentdir);
			  			echo "<a href=\"playem.php?dir=".$currentdir."&amp;guest=".$guestname."\" title=\"return to current folder\">clickey to returney to folder</a>\n";
					}
					fclose($handle);
		  		} else {
					echo "error?? attempted $filename to $filetoappend";
		  		}
		  		// end message
				echo "<br/></div>\n";
				// end mainpage
				echo "</div>\n";
			}

	  	} else {

			$isrootdir = true;
			if (isset ($_GET['dir'])) {
			// read directory for music and folders
				$currentdir = html_entity_decode($_GET['dir']);
				echo "<!-- $currentdir -->";
// GLASTO			if ($currentdir != "/mnt/2tb_USB_hard_disc/p_music") $isrootdir = false;
				if ($currentdir != "/home/glasto/scanpi/music") $isrootdir = false;

			} else {
//				$currentdir = ".";
// GLASTO			$currentdir = "/mnt/2tb_USB_hard_disc/p_music";
				$currentdir = "/home/glasto/scanpi/music";
			}
			echo "Folder now looking in :: ".$currentdir." \n";
			echo "<!-- $isrootdir -->";
			if (!$isrootdir) {
				$lastslash = strrpos($currentdir, "/");
				$uponedir = substr($currentdir,0,$lastslash);
				$uponedir = str_replace(' ','%20', $uponedir);
				$uponedir = str_replace('+', '%2b', $uponedir);
				$uponedir = str_replace('&', '%26', $uponedir);
				echo "<br/><a href=\"playem.php?guest=".$guestname."&amp;dir=".htmlentities($uponedir)."\" title=\"up one dir to ".$uponedir."\">up one dir.</a>\n";
				echo "<br/><a href=\"playem.php?guest=".$guestname."\" title=\"back to top directory\">back to top dir..</a>\n";
			}
			echo "<br/><a href=\"index.php\" title=\"exit this thingy\">leave this place...</a><br /><br/>\n";

			$Folder = opendir($currentdir);
			$dirsexist = false;
			$filesexist = false;
			if ($Folder != false) {
				while (false !== ($file = readdir($Folder))) {
					if ($file != "." && $file != ".." && $file != "guestlists") {
						clearstatcache();
						if (is_dir($currentdir."/".$file)) {
							$dirs[] = $file;
							$dirsexist = true;
						} else {
							$files[] = $file;
							$filesexist = true;
						}
					}
				}
			closedir($Folder);
			}

			if ($dirsexist) natcasesort($dirs);
			if ($filesexist) natcasesort($files);

			if ($dirsexist) {
				echo "Folders.... clicky to change into folder...<br/>\n";
				foreach ($dirs as $dirun) {
					$realdirun = str_replace(' ', '%20', ($currentdir."/".$dirun));
					$realdirun = str_replace('+', '%2b', $realdirun);
					$realdirun = str_replace('&', '%26', $realdirun);
					echo "<a href=\"playem.php?dir=".$realdirun."&amp;guest=".$guestname."\" title=\"open this folder\">".$dirun."</a><br/>\n";
				}
			} else {
				echo "No Folders in this dir...<br/>\n";
			}

			if ($filesexist) {
				echo "<br/>Music Files in this Folder... clicky '+' to add to your playlist, or '&gt;' to play.<br/>\n";
				foreach ($files as $file) {
					if ( preg_match('/mp3/i',$file) ) {
						echo "<div class='filelist'>\n";

						$realname = preg_replace('/.*\/(.*?)/i', '$2', $file);
						// remove the extra '/' and swap spaces out
						$messedfile = substr(str_replace(' ', '%20', $currentdir)."/".rawurlencode($realname),1);
						$messedfile = str_replace('+', '%2b', $messedfile);
						$messedfile = str_replace('&', '%26', $messedfile);
//						$fullurl = "http://192.168.1.227/".$messedfile;
						$fullurl = "http://".$rootpi."/".$messedfile;
						$fullurl = str_replace(' ', '%20', $fullurl);
						$fullurl = str_replace('+', '%2b', $fullurl);
						$fullurl = str_replace('&', '%26', $fullurl);
						echo "<div class='fileitem'><a class='playlink' href=\"".$fullurl."\" title=\"play this track\">&gt;</a></div>\n";
						$currentdir = str_replace(' ', '%20', $currentdir);
						$currentdir = str_replace('+', '%2b', $currentdir);
						$currentdir = str_replace('&', '%26', $currentdir);
						$fullfile = str_replace(' ', '%20', ($currentdir."/".$file));
						$fullfile = str_replace('+', '%2b', $fullfile);
						$fullfile = str_replace('&', '%26', $fullfile);
						echo "<div class='fileitem'><a class='addlink' href=\"playem.php?file=".$fullfile."&amp;dir=".htmlentities($currentdir)."&amp;guest=".$guestname."\"";
						echo " title=\"add this track to the playlist\">+</a></div>\n";
						$file = str_replace('&', '&amp;', $file);
						echo "<div class='fileitem'>$file</div>\n</div>\n";
					} else {
						echo "<span class='hidden'>not an MP3 :: $file</span>\n";
					}
				}
			} else {
				echo "<br/><br/>No Files in this dir...<br/>\n";
			}
			echo "</div>\n";

		}
	}
} else {
	echo "<p>What no guest in the URL - I spot some cheating... bye bye</p>\n";
	echo "</div>";
}
        include "bottomlnk.php";
?>
