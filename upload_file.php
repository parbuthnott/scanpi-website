<?php
   session_start();
   $pagetype='upload_file';
   $subpagetype='upload';
   include "connect_up.php";

   $rootname = 'upload_file.php';
   include "lhs.html"; ?>

   <div id="main">
      <h1>Uploaded.</h1>

      <?php
         $artist = "0";
         $album = "0";

         if (isset($_POST["artist"])) $artist = $_POST["artist"];
         if (isset($_POST["album"])) $album = $_POST["album"];

         if ($artist == "0" || $artist == "" || $album == "0" || $album == "") {
            echo "<h3>No artist or album entered?</h3>";
            echo "artist=".$artist.", album=".$album."<br/>";
            echo "<a href='upload.php'>Try again?</a>";
         } else {

            echo "Artist : " . $artist . "<br/>";
            echo "Album : " . $album . "<br/>";

            if ($_FILES["file"]["error"] > 0) {
               echo "<h3>Summit went awry...</h3>";
               echo "Return Code: " . $_FILES["file"]["error"] . "<br/>";
               echo "<a href='upload.php'>Try again?</a>";
            } else {
               echo "Upload: " . $_FILES["file"]["name"] . "<br/>";
               echo "Type: " . $_FILES["file"]["type"] . "<br/>";
               echo "Size: " . ($_FILES["file"]["size"]) . " bytes<br/>";
               echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br/>";

// BETTER FOR SPACE, but no write permissions
//             $dirname  = "/mnt/2tb_USB_hard_disc/p_music/xxx_shared/".$artist."/".$album;
               $dirname  = "/mnt/scanpi/sharer/xxx_shared/".$artist."/".$album;
               if (!is_dir($dirname)) {
                   mkdir($dirname, 0755, true);
               }

               if (file_exists($dirname."/".$_FILES["file"]["name"])) {
                  echo $_FILES["file"]["name"]." already exists. ";
               } else {
                  move_uploaded_file($_FILES["file"]["tmp_name"], $dirname."/".$_FILES["file"]["name"]);
                  echo "<h3>Success! [it would seem]</h3>";
                  echo "Stored in: ".$dirname."/".$_FILES["file"]["name"]."</br>";
                  echo "<a href='upload.php'>Upload a nuther</a>";
               }
            }
         }
      ?>
   </div>

   <?php include "foot.html"?>
