<?php
   session_start();
   $pagetype='home';
   $subpagetype='search';
   include "connect_up.php";

   $rootname = 'search.php';
   include "lhs.html"; ?>

   <div id="main">
      <h1>Search.</h1>

      <?php
         $term = '0';
         if (isset($_GET['term'])) $term = $_GET['term'];
         if (isset($_POST['term'])) $term = $_POST['term'];

         echo "<h2>Type something to search for ...</h2>";
         echo "<form name='search'>";

         if ($term == '0') {
            echo "<input type='text' name='term' id='term' value='' />";
            echo "<input type='submit' />";
            echo "</form>";
         } elseif (strlen($term) < 4) {
            echo "<p>Please enter more than 3 characters to search for</p>";
            echo "<input type='text' name='term' id='term' value='".$term."' />";
            echo "<input type='submit' />";
            echo "</form>";
         } elseif (strpos($rootmusicdir, $term)) {
            echo "<p>Search term contained in Root Music Directory ... sorry ... [ $term | $rootmusicdir ]</p>";
            echo "<input type='text' name='term' id='term' value='".$term."' />";
            echo "<input type='submit' />";
            echo "</form>";
         } else {
            echo "<input type='text' name='term' id='term' value='".$term."' />";
            echo "<input type='submit' />";
            echo "</form>";

            //COMING SOON??? echo "<a href=\"plaything.php?band=".urlencode($band)."\" title=\"queue all \">Queue All</a>.</h2>\n";


            $return = exec("grep -i '$term' $rootshellplayerdir/allmusic.txt", $resultlist);
            echo "<h2>Results: '$term' [ total found : ".count($resultlist)." ]</h2>";
            $albumcount = 0;
            if (count($resultlist) > 50) {
               echo "<p class='clean'>More than 50 results ... truncated to 50</p>";
               $resultlist = array_slice($resultlist, 0, 50);
            } elseif (count($resultlist) == 0) {
               echo "<p class='clean'>No results ... try entering something else?</p>";
            }
            foreach ($resultlist as &$result) {
                $resultdisplay = str_replace($rootmusicdir,"",$result);
                $resultdisplay = str_replace("/"," - ",$resultdisplay);
                $resultdisplay = "<a href='javascript:$(\"#expandable_$albumcount\").toggle();'>&gt;&gt;</a> ".substr($resultdisplay,3);
                echo "<div class='resultitem'>$resultdisplay</div>\n";
                echo "<div class='expandable' id='expandable_$albumcount'>";
                   $resultdata = explode("/", $result);
                   $band = $resultdata[4];
                   $cover = $resultdata[5];
                   echo "<!-- DEBUG:: band=$band, cover=$cover, albumcount=$albumcount ::-->";
                   showBrowseItem($band, $cover, $albumcount);
                   $albumcount++;
                echo "</div>\n";
            }
            echo "<p class='clean'>Alternatively, just <a onclick=\"history.go(-1);\" title=\"go back\">go back</a>.</p>\n";

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
