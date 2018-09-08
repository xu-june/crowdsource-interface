<?php 
//<!-- Displays images -->
session_start();
$uuid = $_SESSION['pid']; 
$phase = $_POST['phase'];
$obj = $_POST['obj'];

 // Displays the images
$dest_path = dirname(__FILE__) .'/images/p' . $uuid . '/t' .$_SESSION['trial'] .'/'. $phase . '/' . $obj . '/';
$files = glob($dest_path."*.png");
$numFiles = count($files);
//echo $dest_path."*.png";

echo $numFiles;
// echo $numFiles ." images taken";
// echo "<p></p>";
// 
// for ($i=0; $i<$numFiles; $i++)
// {
//     $num = $files[$i];
//     $src = 'images/p' . $uuid . '/t' .$_SESSION['trial'] .'/'. $phase . '/' . $obj . '/'.($i+1).".png";
//     echo "<img src='".$src."' width='45%'>&nbsp;&nbsp;";
// }

?>