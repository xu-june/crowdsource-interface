<?php
	session_start();
	include 'connectDB.php';
	include 'header.php';
    savePageLog($_SESSION['pid'], basename($_SERVER['PHP_SELF']));

	// enter participant info	
	$pq1 = $_POST["pq1"];
	$pq2 = $_POST["pq2"];
	$pq3 = $_POST["pq3"];
	$pq4 = $_POST["pq4"];
	$sql = "UPDATE participant_info set `pq1`='".$pq1."', `pq2`='".$pq2."', `pq3`='".$pq3."', `pq4`='".$pq4."' WHERE `participant_id`=".$_SESSION['pid'].";";
	execSQL($sql);
    
    // update participant status
    $date = date("Y-m-d H:i:s");
    $time = round(microtime(true) * 1000);
	$sql = "UPDATE participant_status set `status`='COMPLETE', `last_update_time`='"
		.$time."', `last_update_date`='".$date."'WHERE `participant_id`=".$_SESSION['pid'].";";
	execSQL($sql);
    
    
    // update state
    $sql = "UPDATE variables set `phase`='end', `upload_cnt_obj1`=5, `upload_cnt_obj2`=5, `upload_cnt_obj3`=5, `subset_cnt_obj`=3, `subset_cnt_num`=0  "
            ."where `participant_id`=".$_SESSION['pid']." and `trial`=".$_SESSION['trial'].";";
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
        
        <form id='codeForm' action='background.php' method='post'>
            <div class="form-group row">
                <div class="col-10">
                    <input type="hidden" id="code" name="code" value="<?=$_SESSION['pcode']?>">
                </div>
            </div>
        </form>
        
        <button type="button" class="btn btn-primary" onclick="document.getElementById('codeForm').submit();">Repeat this study with other objects</button>

<?php
	include 'footer.php';
?>