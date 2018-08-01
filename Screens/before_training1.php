<?php
	include 'header.php';
?>

<!doctype html>
<html lang="en">
  <head>
  <?php printMetaInfo(); ?>
  <title>
    	Training 1
    </title>
  </head>
  
	<body>
		<div class="mt-3 mb-3 mr-3 ml-3">
	  		<?php printProgressBar(4); ?>
    
			<h3>See if you can do better than our machine!</h3>
			<p>Try adding your own images of your objects.</p>

			<button type="button" class="btn btn-default" onclick="window.location.href='training1.php'">Next</button>
		</div>
	</body>
</html>