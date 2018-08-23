<?php 
//<!- - Uploads images to server -->
// <!-- PHP code adapted from http://php.net/manual/en/features.file-upload.php and https://gist.github.com/projectxcappe/1220777/9ec6a7e62fb9d7c9a93bd834fb434d7ae25ed6f5 -->

// Configuring errors
ini_set('display_errors',1);
// error_reporting(E_ALL); 
session_start();
include 'connectDB.php';

$uuid = $_SESSION['pid']; 
$phase = $_POST['phase'];
if (isset($_POST['ratio'])) {
	$_SESSION['ratio'] = $_POST['ratio'];
}
$objectname = str_replace(' ', '_', $_POST['objectname']);

$img = $_POST['imgBase64'];
$img = str_replace('data:image/png;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$fileData = base64_decode($img);

//saving
$filename = $_POST['obj_count'];

$ext = 'png';
$dest_path = dirname(__FILE__) . '/images/p' . $uuid . '/t' .$_SESSION['trial'] .'/'. $phase . '/' . $objectname . '/';
$img_path = $dest_path . $filename . '.' . $ext;
file_put_contents($img_path, $fileData);
// echo $uuid."<br>";
// echo $phase."<br>";
// echo $objectname."<br>";

if (strpos($phase, 'test') === 0) {
	require(dirname(__FILE__).'/../TOR/rest_client.php');
	$puuid = 'p' . $uuid;
	// send an image to the server for testing
	$label = upload_and_test($puuid, $phase, $img_path);
	// return the testing result, label
	echo $label; 
} else {
	echo $img_path; 
}


	
	$date = date("Y-m-d H:i:s");
	$time = round(microtime(true) * 1000);
	$sql = "INSERT INTO action_logs "
			."(`participant_id`, `trial`, `event`, `time`, `date`) VALUES ("
			.$uuid.",".$_SESSION['trial'].", 'upload-".$phase . "-" . $objectname . "-".$filename."', '".$time."', '".$date."');";
	execSQL($sql);
?>
