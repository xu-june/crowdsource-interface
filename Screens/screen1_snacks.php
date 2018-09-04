

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
			<p>Before going to the next step, please have 3 distinct snack packs in your proximity. This could be anything from potato chips, pretzels, or any snacks that you happen to have that is in a small package. </p>
			
			For example: <br>

			<img src="images/chips.jpg" width="30%"> Object 1: <strong>Potato chips</strong> <br>
			<img src="images/fritos.png" width="30%"> Object 2: <strong>Fritos</strong> <br>
			<img src="images/pretzels.png" width="30%"> Object 3: <strong>Pretzels</strong> <br>

			<div align='right'>
				<button class="btn btn-primary" onclick="window.location.href='objects.php'">Next ></button>
			</div>
		
        
<?php
	include 'footer.php';
?>