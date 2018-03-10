<?php
   session_start();
   $pagetype='upload';
   $subpagetype='upload';
   include "connect_up.php";

   $rootname = 'upload.php';
   include "lhs.html"; ?>

   <div id="main">
      <h1>Upload.</h1>

      <?php

         $ua = $_SERVER['HTTP_USER_AGENT'];
         $showform = true;
         $checker = array(
            'iphone'=>preg_match('/iPhone|iPod|iPad/', $ua),
            'blackberry'=>preg_match('/BlackBerry/', $ua),
            'android'=>preg_match('/Android/', $ua),
         );

         if ($checker['iphone']) {
            // do something for iPhone
            echo "<h3>iPhone detected...</h3>";
            echo "<h2>YOUR DEVICE IS RUBBISH</h2>";
            echo "<p>please upgrade! <a href='https://discussions.apple.com/thread/2714162?start=0&tstart=0'>See here.</a></p>";
            $showform = false;
         } elseif ($checker['android']) {
            // do something for Android
            echo "<p>Android detected...</p>";
         } else {
            // do something for Windows / Blackberry / other?
            echo "<p>Neither iPhone nor Android detected.</p>";
         }
         if ($showform) {
         ?>

            <form action="upload_file.php" method="post" enctype="multipart/form-data">
            <fieldset>
               <p>Pick a file and upload - for MP3s</p>
               <p><label for="file">Artist:</label> <input type="text" name="artist" id="artist" /></p>
               <p><label for="file">Album:</label> <input type="text" name="album" id="album" /></p>
               <p><label for="file">Filename:</label> <input type="file" name="file" id="file" /></p>
               <p><input type="submit" name="submit" value="Upload" /></p>
            </fieldset>
            </form>

         <?php
         }

      // http://picupapp.com/ ... and ... URL of "fileupload2://".
      // OR https://itunes.apple.com/us/app/icab-mobile-web-browser/id308111628?mt=8 ??
      ?>
   </div>

   <?php include "foot.html"?>
