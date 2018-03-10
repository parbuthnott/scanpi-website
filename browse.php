<?php
   session_start();
   $pagetype='home';
   $subpagetype='browse';
   include "connect_up.php";

   $rootname = 'browse.php';
   include "lhs.html"; ?>

   <div id="main">
      <h1>Browse.</h1>

      <?php
         // #extension# View historical playing?
         // #option# use mbrainz track listing &amp; hope that they match the PI version?
         // #option# use mbrainz track listing then Jukebox (or similar) library function?

         $band = '0';
         if (isset($_GET['band'])) $band = $_GET['band'];
         if (isset($_POST['band'])) $band = $_POST['band'];

         if ($band == '0') {
            echo "<h2>Pick a band from the list ...</h2>";
            $musicFolder = opendir($rootmusicdir);
            while (false !== ($banddir = readdir($musicFolder))) {
               if ($banddir != "." && $banddir != "..") { // && is_dir($bands)) {
                  $bands[] = $banddir;
               }
            }
            closedir($musicFolder);
            natcasesort ($bands);
            $bandnum = '0'; $dun = '0';
            $halftotalbands = count($bands)/2;
            echo "<!-- halftotalbands: $halftotalbands -->";
            echo "<ul class='side'>\n";
            foreach ($bands as $banditem) {
               $bandnum++;
               if ($bandnum > $halftotalbands && $dun == '0') {
                  echo "</ul><ul class='side'>\n";
                  $dun++;
               }
               echo "<li><a href=\"browse.php?band=".urlencode($banditem)."\" title=\"browse all by this band\">$banditem</a></li>\n";
            }
            echo "</ul>\n";
         } else {
            echo "<h2>Band: $band ";
            echo "<a href=\"plaything.php?band=".urlencode($band)."\" title=\"queue all albums\">Queue All</a>.</h2>\n";
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

            $albumcount = 0;
            foreach ($albums as $cover) {
               echo "<!-- DEBUG:: band=$band, cover=$cover, albumcount=$albumcount ::-->"; 
               showBrowseItem($band, $cover, $albumcount);
               $albumcount++;
            }
            echo "<p>Alternatively, just <a onclick=\"history.go(-1);\" title=\"go back\">go back</a>.</p>\n";

            echo "<div id=\"jpfloat\">";
            echo "   <div id=\"jpfloat_jplayer\">Player</div>\n";
            echo "   <div id=\"jp_container\" class=\"demo-container\"><p>\n";
            echo "      <span class=\"play-state\">p-s</span> : <span class=\"track-name\">nothing</span>\n";
            echo "      at <span class=\"extra-play-info\">e-p-i</span> of <span class=\"jp-duration\">j-d</span>\n";
            echo "      , which is <span class=\"jp-current-time\">j-c-i</span>\n";
            echo "      </p><ul>\n";
            echo "      <li><a class=\"jp-play\" href=\"#\">Play</a></li>\n";
            echo "      <li><a class=\"jp-pause\" href=\"#\">Paus</a></li>\n";
            echo "      <li><a class=\"jp-stop\" href=\"#\">Stop</a></li>\n";
            echo "      <li><a class=\"jp-mute\" href=\"#\">Mute</a></li>\n";
            echo "      <li><a class=\"jp-unmute\" href=\"#\">Unmute</a></li>\n";
            echo "      </ul><ul>\n";
            echo "      <li><a class=\"jp-volume-bar\" href=\"#\">|&lt;----------------&gt;|</a></li>\n";
//            echo "      <li><a class=\"jp-volume-max\" href=\"#\">Max</a></li>\n";
            echo "   </ul></div>\n";
/*
*** COMING SOON
            echo "   <div class=\"jp-playlist\"><ul><li></li></ul></div>\n";
*/
            echo "</div>\n";
         }

      ?>

   </div>

   <?php include "foot.html"?>
