
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
			<p>Before going to the next step, please have 3 distinct spices in your proximity. This could be any spice as long as it's in a cylindrical container. </p>
			
			For example: <br>

			<img src="images/cinnamon.jpg" width="30%"> Object 1: <strong>Cinnamon</strong> <br>
			<img src="images/nutmeg.jpeg" width="30%"> Object 2: <strong>Nutmeg</strong> <br>
			<img src="images/chilipowder.JPG" width="30%"> Object 3: <strong>Chili powder</strong> <br>

			<div align='right'>
				<button class="btn btn-primary" onclick="window.location.href='objects.php'">Next</button>
			</div>
		
        
<?php
	include 'footer.php';
?>