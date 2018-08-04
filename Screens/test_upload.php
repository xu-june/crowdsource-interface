<?php 
//<!- - Uploads images to "test0" folder in server -->
// <!-- PHP code adapted from http://php.net/manual/en/features.file-upload.php and https://gist.github.com/projectxcappe/1220777/9ec6a7e62fb9d7c9a93bd834fb434d7ae25ed6f5 -->

// Configuring errors
ini_set('display_errors',1);
// error_reporting(E_ALL); 
session_start();

$img = $_POST['imgBase64'];
$img = str_replace('data:image/png;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$fileData = base64_decode($img);
//saving
$filename = $_POST['filename'];


$obj = $_POST['object_name'];  // temporary object name
$ext = 'png';


$uuid = $_SESSION['pid']; 
$phase = $_POST['phase'];
$dest_path = dirname(__FILE__) . '/images/p' . $uuid . '/t' .$_SESSION['trial'] .'/'. $phase . '/' . $obj . '/';
$img_path = $dest_path . $filename . '.' . $ext;

//save image file
file_put_contents($img_path, $fileData);

// send request to TOR server
require(dirname(__FILE__).'/../TOR/rest_client.php');
// send an image to the server for testing
$label = upload_and_test($uuid, $phase, $img_path);
// return the testing result, label
echo $label; 

?>
