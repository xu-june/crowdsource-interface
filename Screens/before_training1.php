<?php
    session_start();
    include 'connectDB.php';
    include 'header.php';
    savePageLog($_SESSION['pid'], "before_training1");
?>

<!doctype html>
<html lang="en">
  <head>
  <?php 
  	printMetaInfo(); 
  	// Initializing array for training objects
  	$_SESSION['subselectObj'] = array();
  ?>
  <title>
    	Training 1
    </title>
  </head>
  
	<body>
		<div class="mt-3 mb-3 mr-3 ml-3">
	  		<?php printProgressBar(4); ?>
    
			<h3>See if you can do better than our machine!</h3>
			<p>Try adding your own images of your objects.</p>
			
			<p>You will take 30 pictures of the requested object at a time. 
	        <p>Once you complete 30 images for each object, click <mark>Next</mark> to know what to train next! </p>


			<button type="button" class="btn btn-default" onclick="window.location.href='training1.php'">Next</button>
		</div>
	</body>
</html>