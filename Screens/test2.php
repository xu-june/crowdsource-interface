<?php 
    session_start();
    include 'connectDB.php';
    include 'header.php';
    $phase = "test2";
    $progress = 16;
    $next = 'feedbackscreen2.php';
    savePageLog($_SESSION['pid'], $phase);
    
    
	$obj_index = $_SESSION['order']['train2'][2];
	$uuid = $_SESSION['pid']; 

	// Code from https://www.formget.com/php-checkbox/
	if(isset($_POST['selections']) && is_array($_POST['selections']))
	{ //to run PHP script on submit
		if(!empty($_POST['selections']))
		{
			// Copy each file name into $scr5_subselect20
			foreach($_POST['selections'] as $selected)
			{
				$subselect[] = $selected;
			}
		}
	}
	
	$sql = "UPDATE Objects set `subset2_".$obj_index."_1`='".implode(":", $subselect)."'"
		." WHERE `participant_id`=".$uuid.";";
	execSQL($sql);
    
    
    include 'test_general.php';
?>

<!-- Uploads images to "test1" folder in server -->
<!-- PHP code adapted from http://php.net/manual/en/features.file-upload.php and https://gist.github.com/projectxcappe/1220777/9ec6a7e62fb9d7c9a93bd834fb434d7ae25ed6if5 -->

