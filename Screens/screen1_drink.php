<?php
	include 'header.php';
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
		
        
		</div>
	</body>
</html>

<?php
  // initialize an object recognizer for a user
  $uuid = "12345"; // NOTE: for testing
  require(dirname(__FILE__).'/../TOR/rest_client.php');
  // trigger the recognizer initialization
  init_recognizer($uuid);
?>