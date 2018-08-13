<?php
	// session_start();
	// include 'connectDB.php';
	include 'header.php';
	// savePageLog($_SESSION['pid'], "screen1_drink");

	// $q4 = $_POST["bq4"];
	// $q5 = $_POST["bq5"];
	// $q6 = $_POST["bq6"];
	// $q7 = $_POST["bq7"];
	// $sql = "UPDATE participant_info set `bq4`='".$q4."', `bq5`='".$q5."', `bq6`='".$q6."', `bq7`='".$q7."' WHERE `participant_id`=".$_SESSION['pid'].";";
	// execSQL($sql);
?>

<!doctype html>
<html lang="en">
  <head>
  <?php printMetaInfo(); ?>
  <title>
    	Data Collection Prerequisite
    </title>
  </head>
  
	<body>
		<div class="mt-3 mb-3 mr-3 ml-3">
			<?php printProgressBar(4); ?>
		
			<h3>Data Collection Prerequisite</h3>
			<p>Before going to the next step, please have 3 distinct cereals in your proximity. This could be any cereal as long as it's in a box. </p>
			
			For example: <br>

			<img src="old/images/luckycharms.jpg" width="30%"> Object 1: <strong>Lucky Charms</strong> <br>
			<img src="old/images/cheerios.jpeg" width="30%"> Object 2: <strong>Cheerios</strong> <br>
			<img src="old/images/frostedflakes.jpg" width="30%"> Object 3: <strong>Frosted Flakes</strong> <br>

			<div align='right'>
				<button class="btn btn-default" onclick="window.location.href='objects.php'">Next ></button>
			</div>
		
        
<?php
	include 'footer.php';
?>