<?php
	include 'header.php';
?>

<!doctype html>
<html lang="en">
  <head>
  <?php printMetaInfo(); ?>
  <title>
    	Test 1
    </title>
  </head>
  
	<body>
		<div class="mt-3 mb-3 mr-3 ml-3">
	  		<?php printProgressBar(3); ?>
       
			<h3>Now, can our system tell which is which?</h3>
			<p>Continue to find out!</p>

			<button type="button" class="btn btn-default" onclick="window.location.href='test1.php'">Next</button>
		
		
		</div>
	</body>
</html>