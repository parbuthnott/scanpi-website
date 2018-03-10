<?php
   session_start();
   $pagetype='home';
   $subpagetype='control';
   include "connect_up.php";

   $rootname = 'ccontrol.php';
   include "lhs.html"; ?>

   <div id="main">
      <h1>Queue.</h1>

      <?php
         $cmd = '0';
         $controlaction = '0';
         $played='0';
         if (isset($_GET['controlaction'])) $controlaction = $_GET['controlaction'];
         if (isset($_POST['controlaction'])) $controlaction = $_POST['controlaction'];

         echo "<!-- INCOMING CONTROL ACTION: $controlaction -->";
         if ($controlaction == "skip") {
            $cmd = $rootshellplayerdir."/skip.sh";
         }
         if ($controlaction == "exit") {
            $cmd = $rootshellplayerdir."/exit.sh";
         }
         if ($controlaction == "go") {
            $cmd = $rootshellplayerdir."/go.sh";
         }
         if ($controlaction == "play") {
            $cmd = $rootshellplayerdir."/play.sh";
         }
         if ($controlaction == "reindex") {
            $cmd = $rootshellplayerdir."/reindex.sh";
         }
         if ($controlaction == "whatson") {
            $cmd = $rootshellplayerdir."/whatson.sh";
         }
         if ($controlaction == "played") {
            $played = "true";
         }
         if ($controlaction == "switch") {
            $fromitem = $_GET['from'];
            $toitem = $_GET['to'];
            $cmd = "mv ".$rootshellplayerdir."/queue/".$toitem." ".$rootshellplayerdir."/queue/".$toitem."_temp; ";
            $cmd .= "mv ".$rootshellplayerdir."/queue/".$fromitem." ".$rootshellplayerdir."/queue/".$toitem."; ";
            $cmd .= "mv ".$rootshellplayerdir."/queue/".$toitem."_temp ".$rootshellplayerdir."/queue/".$fromitem.";";
         }
         if ($controlaction == "remove") {
            $controlitem = $_GET['controlitem'];
            $cmd = "mv ".$rootshellplayerdir."/queue/".$controlitem." ".$rootshellplayerdir."/played/";
         }
         if ($controlaction == "requeue") {
            $controlitem = $_GET['controlitem'];
            $cmd = "mv ".$rootshellplayerdir."/played/".$controlitem." ".$rootshellplayerdir."/queue/";
            $played = "true";
         }

         if ($cmd != '0') {
            echo "<!-- <p>about to shell_exec ($cmd)</p>";
            $output = shell_exec($cmd); //." 2>&1 1> /dev/null");
            echo "<p>output was: $output (nothing here means all went well!)</p> -->";
         }

      ?>

      <!-- p>Hmmm. Guess we better put something here that actually works soon...</p -->

      <div class="big_butts">
	     <div id="big_butt_go" class="big_butt"><a href="control.php?controlaction=go">GO</a></div>
         <div id="big_butt_exit" class="big_butt"><a href="control.php?controlaction=exit">EXIT</a></div>
         <div id="big_butt_skip" class="big_butt"><a href="control.php?controlaction=skip">SKIP</a></div>
	     <div id="big_butt_whatson" class="big_butt"><a href="control.php?controlaction=whatson">WHATSON</a></div>
      </div>
      <div class="big_butts">
         <div id="big_butt_browse" class="big_butt"><a href="browse.php">BROWSE</a></div>
         <div id="big_butt_search" class="big_butt"><a href="search.php">SEARCH</a></div>
      </div>

      <div class="big_butts">
	     <div id="big_butt_play" class="big_butt"><a href="control.php?controlaction=play">PLAY</a></div>
         <div id="big_butt_scan" class="big_butt"><a href="scan.php">SCAN</a></div>
         <div id="big_butt_played" class="big_butt"><a href="control.php?controlaction=played">PLAYED</a></div>
      </div>

      <div class="big_butts">
         <div id="big_butt_track" class="big_butt"><a href="control.php?controlaction=track">TRACK</a></div>
	     <div id="big_butt_album" class="big_butt"><a href="control.php?controlaction=album">ALBUM</a></div>
         <div id="big_butt_band" class="big_butt"><a href="control.php?controlaction=band">BAND</a></div>
         <div id="big_butt_covershow" class="big_butt"><a href="control.php?controlaction=covershow">COVERSHOW</a></div>
      </div>

      <div class="big_butts">
	     <div id="big_butt_fmplay" class="big_butt"><a href="control.php?controlaction=fmplay">FMPLAY</a></div>
         <div id="big_butt_move" class="big_butt"><a href="control.php?controlaction=move">MOVE</a></div>
         <div id="big_butt_og" class="big_butt"><a href="control.php?controlaction=og">OG</a></div>
         <div id="big_butt_reindex" class="big_butt"><a href="control.php?controlaction=reindex">REINDEX</a></div>
      </div>

      <!-- div class='container fire'>
	     <div class='spark'></div><div class='spark'></div><div class='spark'></div><div class='spark'></div>
	     <div class='spark'></div><div class='spark'></div><div class='spark'></div><div class='spark'></div>
	     <div class='spark'></div><div class='spark'></div><div class='spark'></div><div class='spark'></div>
	     <div class='spark'></div><div class='spark'></div><div class='spark'></div><div class='spark'></div>
	  </div>
	  <div class='container smoke'>
	     <div class='dust'></div><div class='dust'></div><div class='dust'></div><div class='dust'></div>
	     <div class='dust'></div><div class='dust'></div><div class='dust'></div><div class='dust'></div>
	     <div class='dust'></div><div class='dust'></div><div class='dust'></div><div class='dust'></div>
	     <div class='dust'></div><div class='dust'></div><div class='dust'></div><div class='dust'></div>
	  </div -->

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
                  $prevqueueitem = '0';
                  foreach ($files as $queueitem) {
                     echo "<!-- about to read :".$rootshellplayerdir."/queue/".$queueitem." -->";
                     $filedetails = file_get_contents($rootshellplayerdir."/queue/".$queueitem);
                     $detailsarray = explode("/", $filedetails);
                     echo "<li><a class='queuelink' href=\"control.php?controlaction=remove&amp;controlitem=".$queueitem."\" title=\"remove item from queue\">x</a>\n";
                     if ($prevqueueitem != '0') {
                        echo "<a class='queuelink' href=\"control.php?controlaction=switch&amp;from=".$queueitem."&amp;to=".$prevqueueitem."\" title=\"move up the queue\">&uarr;</a>\n";
//                      echo "<a class='queuelink' href=\"control.php?controlaction=switch&amp;to=".$queueitem."&amp;from=".$prevqueueitem."\" title=\"move down the queue\">&darr;</a>\n";
                     }
//                   echo "(".$queueitem.")";
                     echo " ".$detailsarray[4]." : ".$detailsarray[5]." : ".$detailsarray[6]."</li>\n";

                     $prevqueueitem = $queueitem;
                  }
                  echo "</ul></div>\n";
               } else {
                  echo "<p>Nothing in queue...</p>";
               }

            } else {
               echo "<p>NOT FOUND: ".$rootshellplayerdir."/queue</p>";
            }
         } else {

            echo "<h2>Played items... (newest at top)</h2>";
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
                  $files = array_reverse($files);
                  echo "<div class='queueitem'><ul class='side'>\n";
                  foreach ($files as $queueitem) {
                     $filedetails = file_get_contents($rootshellplayerdir."/played/".$queueitem);
                     $detailsarray = explode("/", $filedetails);
                     echo "<li><a class='queuelink' href=\"control.php?controlitem=requeue&amp;controlitem=".$queueitem."\" title=\"requeue item\">+</a>";
//                   echo "(".$queueitem.")";
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
