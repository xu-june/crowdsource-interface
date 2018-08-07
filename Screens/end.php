<?php
	session_start();
	include 'connectDB.php';
	include 'header.php';
    savePageLog($_SESSION['pid'], "end");

	// enter participant info	
	$age = $_POST["age"];
	$gender = $_POST["gender"];
	$q1 = $_POST["pq1"];
	$q2 = $_POST["pq2"];
	$q3 = $_POST["pq3"];
	$q4 = $_POST["pq4"];
	$sql = "INSERT INTO participant_info (`participant_id`, `age`, `gender`, `pq1`, `pq2`, `pq3`, `pq4`, `time`, `date`) VALUES ("
		.$pid.",".$age.", '".$gender."', '".$q1."', '".$q2."', '".$q3."', '".$q4."','".$time."','".$date."');";
	execSQL($sql);

	// update participant status
	$sql = "UPDATE participant_status set `status`='COMPLETE', `last_update_time`='"
		.$time."', `last_update_date`='".$date."'WHERE `participant_id`=".$pid.";";
	execSQL($sql);
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <script src="./js/jquery-3.3.1.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>

    <title>Congratulations</title>
  </head>
  <body>
    <div class="mt-3 mb-3 mr-3 ml-3">
        <h1>Congratulations!</h1>
        <p>You finished all steps in our study. Please enter the following code in our HIT page in Amazon Mechanical Turk to get compensation. </p>
        
        <div id='code' align='center'> <?=$_SESSION['pcode']?> </div>
         <br>
         
        <p>If you want to do this study again with other objects, go to the introduction page and enter this code or touch the button below. 
        You are not allowed to use the objects that are already used in the previous study.</p>
        <button type="button" class="btn btn-default" onclick="window.location.href='index.php'">Repeat this study with other objects</button>
    </div> 
  </body>
</html>