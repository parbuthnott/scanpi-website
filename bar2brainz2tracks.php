<?php
	session_start();
	$pagetype='home';
	$subpagetype='homepage';
	include "connect_up.php";

	$rootname = 'bar2brainz2tracks.php';
	include "lhs.html"; ?>

	<div id="main">
		<h1>Scanned.</h1>

		<?php
			// #option# use mbrainz to look up cover image?
			// #extension# have a look in $bandsort directory if $band does not exist.
			// #option# use mbrainz track listing & hope that they match the PI version?
			// #option# use mbrainz track listing then Jukebox (or similar) library function?

			$barcode = '0';
			if (isset($_GET['barcode'])) $barcode = $_GET['barcode'];
			if (isset($_POST['barcode'])) $barcode = $_POST['barcode'];

			// pic2shop on iPhone...
			if (isset($_GET['ean'])) $barcode = $_GET['ean'];
			if (isset($_POST['ean'])) $barcode = $_POST['ean'];

			// pic2shop on android...
			$source = '0';
			if (isset($_GET['source'])) $source = $_GET['source'];
			if (isset($_POST['source'])) $source = $_POST['source'];

			if ($source != '0') {
				if (isset($_GET['q'])) $barcode = $_GET['q'];
				if (isset($_POST['q'])) $barcode = $_POST['q'];
			}
			echo "<!-- INCOMING BARCODE: $barcode -->";
			$band = '0';
			$album ='0';

			if ($barcode != '0') {
				$firstsix = substr($barcode, 0, 6);
				echo "<!-- firstsix: $firstsix -->";

				if ($firstsix != 'scanpi') {
					$mbrainzurl = $rootmbrainzurl.$barcode;
					echo "<!-- <a href='$mbrainzurl'>Call Music Brainz ($barcode)</a> -->";
					$mbrainz = file_get_contents($mbrainzurl, "r");
					// echo "<!-- $mbrainz -->";
		/*
					// example response...
					<!-- <?xml version="1.0" encoding="UTF-8" standalone="yes"?>
					<metadata created="2012-11-04T14:12:17.511Z" xmlns="http://musicbrainz.org/ns/mmd-2.0#" xmlns:ext="http://musicbrainz.org/ns/ext#-2.0">
						<release-list count="1" offset="0"><release id="2e90656c-f197-35c3-b20a-9e3321f5a271" ext:score="100">
							<title>Waterloo</title>
							<status>Official</status>
							<text-representation><language>eng</language><script>Latn</script></text-representation>
							<artist-credit><name-credit joinphrase="">
								<artist id="d87e52c5-bb8d-4da8-b941-9f4928627dc8">
									<name>ABBA</name>
									<sort-name>ABBA</sort-name>
									<disambiguation></disambiguation>
								</artist>
							</name-credit></artist-credit>
							<release-group id="1f78ea53-5e9c-46b6-8cd4-9d8a9c547a85" type="Album">
								<primary-type>Album</primary-type>
							</release-group>
							<date>1993</date>
							<country>DE</country>
							<barcode>731455003420</barcode>
							<asin>B000025KVD</asin>
							<label-info-list><label-info>
								<catalog-number>550 0342</catalog-number>
								<label id="c9faeb9a-9fac-4a4b-96b0-9f879b6ad3c7"><name>Spectrum Music</name></label>
							</label-info></label-info-list>
							<medium-list count="1">
								<track-count>11</track-count>
								<medium><format>CD</format><disc-list count="3"/><track-list count="11"/></medium>
							</medium-list>
						</release></release-list>
					</metadata> -->
		*/
					// the #wrong# way to do the variable setting
					$band = substr($mbrainz, strpos($mbrainz, "<name>")+6, 300);
					$band = substr($band, 0, strpos($band, "</name>"));

					$bandsort = substr($mbrainz, strpos($mbrainz, "<sort-name>")+11, 300);
					$bandsort = substr($bandsort, 0, strpos($bandsort, "</sort-name>"));

					$album = substr($mbrainz, strpos($mbrainz, "<title>")+7, 300);
					$album = substr($album, 0, strpos($album, "</title>"));

		/*
					// the #right# way to do the variable setting
					$mbrainzxml = simplexml_load_file($mbrainzurl);
					$mbrainzxml = new SimpleXMLElement($mbrainz);
					echo "<p>$mbrainzxml</p>";

					$band = $mbrainzxml->{release-list}->{artist-credit}->{name-credit}->artist->name;
					$bandsort = $mbrainzxml->{release-list}->{artist-credit}->{name-credit}->artist->{sort-name};
					$album = $mbrainzxml->{release-list}->release->title;
		*/
				} else {
					$exploded = explode("/", $barcode);
					echo "<!-- take apart scanpi ($barcode) -->";
					$band = $bandsort = $exploded[2];
					$album = $exploded[3];
				}

				if ($band != '0' && $band != '' && $album != '0' && $album != '') {
					echo "<!-- band:$band, bandsort:$bandsort, album:$album -->";

					//lookup files - tracks (mp3) and Folder.jpg
					$coverimage = "no_cover.jpg";

					// rootmusicdir is set up in connect_up.php and defaults to "music"

					if (file_exists($rootmusicdir."/".$band)) {
						echo "<!-- exists: ".$rootmusicdir."/".$band." -->";
						if (file_exists($rootmusicdir."/".$band."/".$album)) {
							echo "<!-- exists: ".$rootmusicdir."/".$band."/".$album." -->";
							echo "<h2>Details and play links below...</h2>";

							showBrowseItem($band, $album);

						} else {
							echo "<h2>Album : ".$album." NOT FOUND. Showing all albums for ".$band."</h2>";
							$bandFolder = opendir($rootmusicdir."/".$band);
							while (false !== ($dir = readdir($bandFolder))) {
								echo "<!-- looking at : ".$rootmusicdir."/".$band."/".$dir." -->";
								if (is_dir($rootmusicdir."/".$band."/".$dir) && $dir != "." && $dir != "..") {
									echo "<!-- isdir : ".$dir." -->";
									if ($albumFolder = opendir($rootmusicdir."/".$band."/".$dir)) {
										echo "<!-- opened : ".$rootmusicdir."/".$band."/".$dir." -->";
										$albums[] = $dir;
										closedir($albumFolder);
									} else {
										echo "<!-- cant read dir: ".$dir." -->";
									}
								}
							}
							closedir($bandFolder);

							foreach ($albums as $cover) {
								showBrowseItem($band, $cover);
							}
						}

					} else {
						echo "<p class='error'>barcode:".$barcode.", band:".$band.", album:".$album." but no files found?</p>";
					}

				} else {
					echo "<p class='error'>barcode:".$barcode.", but no band or album found?</p>";
				}

			} else {
				echo "<p class='error'>No barcode found?</p>";
			}

		?>
	</div>

	<?php include "foot.html"?>

