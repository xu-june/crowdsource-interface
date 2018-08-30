<?php 
//<!- - Uploads images to server -->
// <!-- PHP code adapted from http://php.net/manual/en/features.file-upload.php and https://gist.github.com/projectxcappe/1220777/9ec6a7e62fb9d7c9a93bd834fb434d7ae25ed6f5 -->
session_start();
include 'connectDB.php';
include 'header.php';

// get state variables from database
$query = "SELECT phase, upload_cnt_obj1, upload_cnt_obj2, upload_cnt_obj3 FROM "
    ."variables where participant_id=".$_SESSION['pid']." and trial = ".$_SESSION['trial']." order by time desc";
$latestVar = getSelect($query);

// get states for the current object
$uuid = $_SESSION['pid']; 
$phase = $latestVar['phase'];    
$total_cnt = $latestVar['upload_cnt_obj1']+$latestVar['upload_cnt_obj2']+$latestVar['upload_cnt_obj3'];


$order = $_SESSION['order'][$latestVar['phase']];
$curr_obj_index = getObjectIndex($phase, $total_cnt);
$objectname = $_SESSION["object_names"][$curr_obj_index-1];

$obj_cnt = $latestVar['upload_cnt_obj'.$curr_obj_index];
//echo $phase."--".implode(',', $_SESSION["object_names"])."---".implode(',', $order)."---".$total_cnt."...".$curr_obj_index."---".$objectname;

// save file
$img = $_POST['imgBase64'];
$img = str_replace('data:image/png;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$fileData = base64_decode($img);
$filename = $obj_cnt + 1;
$ext = 'png';

$dest_path = dirname(__FILE__) . '/images/p' . $uuid . '/t' .$_SESSION['trial'] .'/'. $phase . '/' . $objectname . '/';
$img_path = $dest_path . $filename . '.' . $ext;
file_put_contents($img_path, $fileData);

error_log($curr_obj_index."_".$objectname."_".$img_path."_".filesize($img_path));

if (filesize($img_path) < 50000) {
    if (strpos($phase, 'test') === 0) {
        echo $phase."=".$latestVar['upload_cnt_obj1']."=".$latestVar['upload_cnt_obj2']."=".$latestVar['upload_cnt_obj3']."=0=0=".$objectname."=".$curr_obj_index."=[Upload error] try again";
    } else {
        echo $phase."=".$latestVar['upload_cnt_obj1']."=".$latestVar['upload_cnt_obj2']."=".$latestVar['upload_cnt_obj3']."=0=0=".$objectname."=".$curr_obj_index."=[Upload error] try again";
    }
    // write action log
    $date = date("Y-m-d H:i:s");
    $time = round(microtime(true) * 1000);
    $sql = "INSERT INTO action_logs "
            ."(`participant_id`, `trial`, `event`, `time`, `date`) VALUES ("
            .$uuid.",".$_SESSION['trial'].", 'upload-".$phase . "-" . $objectname . "-".$filename."-".($total_cnt+1)."-error', '".$time."', '".$date."');";
    execSQL($sql);
    exit();
}

$label = 'na';
if (strpos($phase, 'test') === 0) {
	require(dirname(__FILE__).'/../TOR/rest_client.php');
	$puuid = 'p' . $uuid;
	// send an image to the server for testing
	$label = upload_and_test($puuid, $phase, $img_path);
	// return the testing result, label
	//echo $label; 
} else {
	//echo $img_path; 
}

// write action log
$date = date("Y-m-d H:i:s");
$time = round(microtime(true) * 1000);
$sql = "INSERT INTO action_logs "
        ."(`participant_id`, `trial`, `event`, `time`, `date`) VALUES ("
        .$uuid.",".$_SESSION['trial'].", 'upload-".$phase . "-" . $objectname . "-".$filename."-".($total_cnt+1)."', '".$time."', '".$date."');";
execSQL($sql);

// update state and return result
$sql = "UPDATE variables set `upload_cnt_obj".$curr_obj_index."`=".($obj_cnt+1)." where `participant_id`=".$uuid." and `trial`=".$_SESSION['trial'].";";
execSQL($sql);

$latestVar['upload_cnt_obj'.$curr_obj_index] += 1;
$curr_obj_index = getObjectIndex($phase, $total_cnt+1);
$objectname = $_SESSION["object_names"][$curr_obj_index-1];
echo $phase."=".$latestVar['upload_cnt_obj1']."=".$latestVar['upload_cnt_obj2']."=".$latestVar['upload_cnt_obj3']."=0=0=".$objectname."=".$curr_obj_index."=".$label;
$_SESSION['phase'] = $phase;

?>
