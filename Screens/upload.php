<?php 
//<!- - Uploads images to server -->
// <!-- PHP code adapted from http://php.net/manual/en/features.file-upload.php and https://gist.github.com/projectxcappe/1220777/9ec6a7e62fb9d7c9a93bd834fb434d7ae25ed6f5 -->

// Configuring errors
ini_set('display_errors',1);
// error_reporting(E_ALL); 
session_start();

$uuid = $_SESSION['pid']; 
$phase = $_POST['phase'];
$objectname = $_POST['objectname'];

$objects_key = '';
if ($phase == 'test0') {
	$objects_key = 'objects_ts0';
} else if ($phase == 'test1') {
	$objects_key = 'objects_ts1';
} else if ($phase == 'test2') {
	$objects_key = 'objects_ts2';
} else if ($phase == 'train1') {
	$objects_key = 'objects_tr1';
} else if ($phase == 'train2') {
	$objects_key = 'objects_tr2';
}
# increment counter
$_SESSION[$objects_key][$objectname]++;
$_SESSION['phase'] = $phase;

$img = $_POST['imgBase64'];
$img = str_replace('data:image/png;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$fileData = base64_decode($img);

//saving
$filename = $_SESSION[$objects_key][$objectname];
$obj = $_POST['objectname'];
$ext = 'png';
$dest_path = dirname(__FILE__) . '/images/p' . $uuid . '/t' .$_SESSION['trial'] .'/'. $phase . '/' . $obj . '/';
$img_path = $dest_path . $filename . '.' . $ext;
file_put_contents($img_path, $fileData);

if (strpos($phase, 'test') === 0) {
	require(dirname(__FILE__).'/../TOR/rest_client.php');
	// send an image to the server for testing
	$label = upload_and_test($uuid, $phase, $img_path);
	// return the testing result, label
	echo $label; 
}

// return the testing result, label
echo ' '.json_encode($_SESSION[$objects_key])."_".$_SESSION['currObj']; 
?>
