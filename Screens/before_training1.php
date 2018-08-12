<?php
    session_start();
    include 'connectDB.php';
    include 'header.php';
    savePageLog($_SESSION['pid'], "before_training1");
    //$_SESSION['test_img_num'] = 10; // temporary value for debugging
?>

<!doctype html>
<html lang="en">
  <head>
  <?php 
  	printMetaInfo(); 
  	// Initializing array for training objects
  	$_SESSION['subselectObj'] = array();
    $_SESSION['step'] = 0; // variable for subset selection
  ?>
  <title>
    	Training 1
    </title>
  </head>
  
	<body>
		<div class="mt-3 mb-3 mr-3 ml-3">
	  		<?php printProgressBar(4); ?>
    
			<h3>Training Images</h3>
			<p>Let’s see if you can train our object recognizer to identify correctly your objects. You can do this by providing many examples of how it looks.</p>
			
			<p>We will randomly choose one of your objects and ask you to take 30 photos of it. 

			
			<div align='right'>
				<button type="button" class="btn btn-default" onclick="window.location.href='training1.php'">Next ></button>
			</div>
		</div>
	</body>
</html>