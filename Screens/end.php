<?php
	session_start();
	include 'connectDB.php';
	include 'header.php';
    savePageLog($_SESSION['pid'], "end");

	// enter participant info	
	$pq1 = $_POST["pq1"];
	$pq2 = $_POST["pq2"];
	$pq3 = $_POST["pq3"];
	$pq4 = $_POST["pq4"];
	$sql = "UPDATE participant_info set `pq1`='".$f1q1."', `pq2`='".$f1q2."', `pq3`='".$pq3."', `pq4`='".$pq4."' WHERE `participant_id`=".$_SESSION['pid'].";";
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
         
        <p>If you want to do this study again with other objects, touch the button below to go to the introduction page, then enter this code. 
        You are not allowed to use the objects that you already used.</p>
        <button type="button" class="btn btn-default" onclick="window.location.href='index.php'">Repeat this study with other objects</button>

<?php
	include 'footer.php';
?>