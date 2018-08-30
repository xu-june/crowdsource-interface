
<!doctype html>
<html lang="en">
  <head>
  <?php printMetaInfo(); ?>
  <title>
    	Data Collection Condition
    </title>
  </head>
  
	<body>
		<div class="mt-3 mb-3 mr-3 ml-3">
			<?php printProgressBar(4); ?>
		
			<h4>Data Collection Condition</h4>
			<p>Before going to the next step, please have 3 distinct canned drinks in your proximity. This could be anything from soda, sparkling water, beer, or any soft drink that you happen to have that is canned. </p>
			
			For example: <br>

			<img src="images/coke.jpg" width="30%"> Object 1: <strong>Coca cola</strong> <br>
			<img src="images/pepsi.jpg" width="30%"> Object 2: <strong>Pepsi</strong> <br>
			<img src="images/sprite.jpg" width="30%"> Object 3: <strong>Sprite</strong> <br>

			<div align='right'>
				<button class="btn btn-primary" onclick="window.location.href='objects.php'">Next ></button>
			</div>
		
        
<?php
	include 'footer.php';
?>