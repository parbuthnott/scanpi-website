<?php
    session_start();
	$pagetype='home';
	$subpagetype='homepage';
	include "connect_up.php";

	$isloggedin='0';

	if (isset($_GET['username']) && $_GET['username'] == 'serious') {
	if (isset($_GET['password']) && $_GET['password'] == 'see33') {
		if (isset($_GET['guestname']) && strlen($_GET['guestname']) > 1 && $_GET['guestname'] != 'firstname.surname') {
			$guestname = $_GET['guestname'];
			$isloggedin='1';
		  	print "<p>you're in... ";
		 	print " you want to link [<a href='playem.php?guest=$guestname' title='add items to this playlist etc'>through here</a>]";
		 	print "<br/>";
		  	print "meanwhile... I'll start your m3u file if you don't have one...</p>";
      			$filetoappend = "guestlists/".$guestname.".m3u";
      			if (!file_exists($filetoappend)) touch($filetoappend);
			if ($handle = fopen($filetoappend, 'ar')) {
				$fstat = fstat($handle);
				if ($fstat['size'] > 7) {
					echo "<div id=\"mainpage\">file already exists... <b>welcome back ".$guestname."</b></div>";
				} else {
					if (fwrite($handle, "#EXTM3U\n") === FALSE) {
						echo "<div id=\"mainpage\"><b>error?? cannot write to file ".$filetoappend."</b></div>";
					} else {
						echo "<div id=\"mainpage\"><br/>appended starter text to ".$filetoappend."</div>";
					}
				}
				fclose($handle);
			} else {
				echo "<div id=\"mainpage\"><b>error?? attempted ".$filename." to ".$filetoappend."</b></div>";
			}
		} else {
 	        	print "<p>** ERROR ** try adding a guestname ...</p>";
		}
	} else {
		print "<p>** ERROR ** you are currently not good enough ...</p>";
	}
	} else {
		print "<p>** ERROR ** you are currently not good enough ...</p>";
	}
	$rootpi = $_SERVER['SERVER_NAME'];
?>
</div>
<?php if ($isloggedin == '0') { ?>
<div id="mainpage">
	Welcome to another product of scanpi.co.uk on <?php echo $rootpi; ?>; some musicy stuff for us to play with...<br/><br/>
	stick in the <b>right</b> pile of stuff in the form and I'll take you to your music guestlists.
	<br/><br/>
	<form id='login' action='' ><div>
		<div>username :	<input type='text' name='username' value='' /></div>
		<div>password :	<input type='text' name='password' value='' /></div>
		<div>guestname :	<input type='text' name='guestname' value='firstname.surname' /></div>
		<div><input type='submit' value='go for guestlists' /></div>
	</div></form>
	<!-- serious see33 -->
</div>
<?php
	} 
	include "bottomlnk.php";
?>
