<?php

   $vartoday = date("dS F Y");

   // testing on scanpi.co.uk
   // $rootpiurl = "http://www.scanpi.co.uk/scanpi";
   // $rootscanurl = "zxing://scan/?ret=";
   // $rootscanreturnurl = URLencode($rootpiurl."/bar2brainz2tracks.php?barcode={CODE}");
   // $roototherscanurl = "pic2shop://scan?callback=";
   // $roototherscanreturnurl = URLencode($rootpiurl."/bar2brainz2tracks.php");
   // $rootmbrainzurl = "http://www.musicbrainz.org/ws/2/release/?query=barcode:";
   // $rootmusicdir = "../music";
   // $rootshellplayerdir = "./shellplayer";

   // final
   $rootpi = $_SERVER['SERVER_NAME'];
   $rootpiurl = "http://".$rootpi."/scanpi";
   $rootscanurl = "zxing://scan/?ret=";
   $rootscanreturnurl = URLencode($rootpiurl."/bar2brainz2tracks.php?barcode={CODE}");

   $roototherscanurl = "pic2shop://scan?callback=";
   $roototherscanreturnurl = URLencode($rootpiurl."/bar2brainz2tracks.php");

   $rootmbrainzurl = "http://www.musicbrainz.org/ws/2/release/?query=barcode:";

   $rootmusicdir = "/mnt/2tb_USB_hard_disc/p_music";
   $rootshellplayerdir = "/mnt/scanpi/shellplayer";

   function showBrowseItem ($sBand, $sAlbum, $albumcount) {
      // SHOULD NOT NEED THIS??
      $rootpi = $_SERVER['SERVER_NAME'];
      $rootmusicdir = "/mnt/2tb_USB_hard_disc/p_music";
      // $rootmusicdir = "../music";
      $coverimage = "no_cover.jpg";
      $trackcounter = 0;
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
            echo "<div class='albumcover'><img src=\"".$rootmusicdir."/".$sBand."/".$sAlbum."/".$coverimage."\" alt=\"$sBand $sAlbum\" title=\"$sBand $sAlbum\" /></div>\n";
         } else {
            echo "<div class='albumcover'><img src=\"$coverimage\" alt=\"$sBand $sAlbum\" title=\"$sBand $sAlbum\" /></div>\n";
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
            echo "<a class=\"queuelink\" href=\"plaything.php?track=".urlencode($track)."&amp;album=".urlencode($sAlbum)."&amp;band=".urlencode($sBand)."\" title=\"add track to the queue\">+</a>\n";

// NOT YET IMPLEMENTED!
//          echo "<a class=\"playnowlink\" href=\"playthingnow.php?track=".urlencode($track)."&amp;album=".urlencode($sAlbum)."&amp;band=".urlencode($sBand)."\" title=\"add track to NEXT on the queue\">&uarr;</a>\n";
//

            if ($trackcounter == 0 && $albumcount == 0) {
               echo "<a class=\"jplayerlink track-default ".$trackcounter.":".$albumcount."\" href=\"http://".$rootpi."/mnt/2tb_USB_hard_disc/p_music/".$urlsBand."/".$urlsAlbum."/".$urltrack."\" title=\"".$track."\">j</a>\n";
/*
*** COMING SOON
               echo "<a class=\"jplayeraddlink track-default-add ".$trackcounter.":".$albumcount."\" href=\"http://".$rootpi."/mnt/2tb_USB_hard_disc/p_music/".$urlsBand."/".$urlsAlbum."/".$urltrack."\" title=\"".$track."\">k</a>\n";
*/
            } else {
               echo "<a class=\"jplayerlink ".$trackcounter.":".$albumcount."\" href=\"http://".$rootpi."/mnt/2tb_USB_hard_disc/p_music/".$urlsBand."/".$urlsAlbum."/".$urltrack."\" title=\"".$track."\">j</a>\n";
/*
*** COMING SOON
               echo "<a class=\"jplayeraddlink ".$trackcounter.":".$albumcount."\" href=\"http://".$rootpi."/mnt/2tb_USB_hard_disc/p_music/".$urlsBand."/".$urlsAlbum."/".$urltrack."\" title=\"".$track."\">k</a>\n";
*/
            }

            echo "$track</li>\n";
            $trackcounter++;
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
   <link rel='stylesheet' href='fire.css' type='text/css' />
   <!-- meta name="google-site-verification" content="WHu2Fe6emfkVVrsFH6Bg0TuKOP9CJD-Zn1M-ucse1Y0" / -->
   <link rel="shortcut icon" href="favicon.ico" />


   <script type="text/javascript" src="jplayer/jquery.min.js"></script>
   <script type="text/javascript" src="jplayer/jquery.jplayer.min.js"></script>
   <script type="text/javascript" src="jplayer/jplayer.playlist.min.js"></script>
   <script type="text/javascript">
   //<![CDATA[

$(document).ready(function(){

   // Local copy of jQuery selectors, for performance.
   var my_jPlayer = $("#jpfloat_jplayer"),
       my_trackName = $("#jp_container .track-name"),
       my_playState = $("#jp_container .play-state"),
       my_extraPlayInfo = $("#jp_container .extra-play-info");

   // Some options
   var opt_play_first = false, // If true, will attempt to auto-play the default track on page loads. No effect on mobile devices, like iOS.
       opt_auto_play = true, // If true, when a track is selected, it will auto-play.
       opt_text_playing = "Now playing", // Text when playing
       opt_text_selected = "Track selected"; // Text when not playing

   // A flag to capture the first track
   var first_track = true;

   // Change the time format
   $.jPlayer.timeFormat.padMin = false;
   $.jPlayer.timeFormat.padSec = false;
   $.jPlayer.timeFormat.sepMin = " min ";
   $.jPlayer.timeFormat.sepSec = " sec";

   // Initialize the play state text
   my_playState.text(opt_text_selected);

   // Instance jPlayer
   my_jPlayer.jPlayer({
      ready: function () {
         $(".track-default").click();
         $(".track-default-add").click();
      },
      timeupdate: function(event) {
         my_extraPlayInfo.text(parseInt(event.jPlayer.status.currentPercentAbsolute, 10) + "%");
      },
      play: function(event) {
         my_playState.text(opt_text_playing);
      },
      pause: function(event) {
         my_playState.text(opt_text_selected);
      },
      ended: function(event) {
         my_playState.text(opt_text_selected);
      },
      swfPath: "jplayer",
      cssSelectorAncestor: "#jp_container",
      supplied: "mp3",
      wmode: "window"
   });

   // Create click handlers for the different tracks
   $(".side .jplayerlink").click(function(e) {
      // alert ("clicked on ..."+$(this).attr('title'));
      my_trackName.text($(this).attr('title'));
      my_jPlayer.jPlayer("setMedia", {
         mp3: $(this).attr("href")
      });
      if((opt_play_first && first_track) || (opt_auto_play && !first_track)) {
         my_jPlayer.jPlayer("play");
      }
      first_track = false;
      $(this).blur();
      return false;
   });

/*
*** COMING SOON
   // create playlist
   var myPlaylist = new jPlayerPlaylist({
      jPlayer: "#jpfloat_jplayer",
      cssSelectorAncestor: "#jpfloat"
   }, [ ], {
      playlistOptions: {
         enableRemoveControls: true
      },
      swfPath: "jplayer",
      supplied: "mp3"
   });

   $(".side .jplayeraddlink").click(function(e) {
      // alert ("clicked add on ..."+$(this).attr('title'));
      myPlaylist.add({
         title: $(this).attr('title'),
         mp3: $(this).attr("href")
      });
      $(this).blur();
      return false;
   });
*/
});

//]]>
</script>
</head>
<body>
<!-- <?php echo "rootpi: $rootpi"; ?> -->
