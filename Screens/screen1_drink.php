<?php
	session_start();
	include 'connectDB.php';
	include 'header.php';

	// get participant id	
	if (!isset($_SESSION['pid'])) {
		$query = "SELECT participant_id, participant_code FROM participant_status where status='INCOMPLETE' order by participant_id";
		$result = getSelect($query);
		$pid = $result['participant_id'];
		$pcode = $result['participant_code'];
		$date = date("Y-m-d H:i:s");
		$time = round(microtime(true) * 1000);

		// enter participant info	
		$age = $_POST["age"];
		$gender = $_POST["gender"];
		$q1 = $_POST["q1"];
		$q2 = $_POST["q2"];
		$q3 = $_POST["q3"];
		$q4 = $_POST["q4"];
		$sql = "INSERT INTO participant_info (`participant_id`, `age`, `gender`, `q1`, `q2`, `q3`, `q4`, `time`, `date`) VALUES ("
			.$pid.",".$age.", '".$gender."', '".$q1."', '".$q2."', '".$q3."', '".$q4."','".$time."','".$date."');";
		execSQL($sql);
	
		// update participant status
		$sql = "UPDATE participant_status set `status`='IN PROGRESS', `trial`=1, `last_update_time`='"
			.$time."', `last_update_date`='".$date."'WHERE `participant_id`=".$pid.";";
		execSQL($sql);
	
		// save page log and session variables
		savePageLog($pid, "screen1_drink");
		$_SESSION['pid'] = $pid;
		$_SESSION['pcode'] = $pcode;
		$_SESSION['trial'] = 1;
	}
	savePageLog($_SESSION['pid'], "screen1_drink");
?>


<!doctype html>
<html lang="en">
  <head>
  <?php printMetaInfo(); ?>
  <title>
    	Background Survey
    </title>
  </head>
  
	<body>
		<div class="mt-3 mb-3 mr-3 ml-3">
			<?php printProgressBar(2); ?>
		
			<h3>Welcome to our study!</h3>
			<p>Before continuing, please choose 3 distinct canned drinks to use! Here are some ideas:</p>

			<img src="images/coke.jpg" width="30%">
			<img src="images/pepsi.jpg" width="30%">
			<img src="images/sprite.jpg" width="30%">
		
			<br>
			<p>Assign each of your objects as Object 1, Object 2, and Object 3 on the next page.</p>

			<button class="btn btn-default" onclick="window.location.href='objects.php'">Next</button>
		
        
<?php
	include 'footer.php';
?>