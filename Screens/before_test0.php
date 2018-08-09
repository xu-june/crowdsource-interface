<?php
	session_start();
	include 'connectDB.php';
	include 'header.php';
	savePageLog($_SESSION['pid'], "before_test0");
	
	function recursive_rmdir($dir) { 
		if( is_dir($dir) ) { 
		  $objects = array_diff( scandir($dir), array('..', '.') );
		  foreach ($objects as $object) { 
			$objectPath = $dir."/".$object;
			if( is_dir($objectPath) )
			  recursive_rmdir($objectPath);
			else
			  unlink($objectPath); 
		  } 
		  rmdir($dir); 
		} 
	  }
	
	// echo dirname(__FILE__) . "/images/";
	$img_base_dir = "images";
	$uuid = $_SESSION['pid']; // NOTE: for testing

	// clear the previous data which may be incomplete
	recursive_rmdir(dirname(__FILE__) . '/' . $img_base_dir . '/p' . $uuid . "/t" . $_SESSION['trial']);
	
	$urlts = dirname(__FILE__) . '/' . $img_base_dir . '/p' . $uuid . "/t" . $_SESSION['trial'] . "/test0/";
	$urltr1 = dirname(__FILE__) . '/' . $img_base_dir . '/p' . $uuid . "/t" . $_SESSION['trial'] . "/train1/";
	$urltr2 = dirname(__FILE__) . '/' . $img_base_dir . '/p' . $uuid . "/t" . $_SESSION['trial'] . "/train2/";
	$urlts1 = dirname(__FILE__) . '/' . $img_base_dir . '/p' . $uuid . "/t" . $_SESSION['trial'] . "/test1/";
	$urlts2 = dirname(__FILE__) . '/' . $img_base_dir . '/p' . $uuid . "/t" . $_SESSION['trial'] . "/test2/";

	// for randomization function in test phases
	$obj1 = str_replace(' ', '_', $_GET["obj1"]);
	$obj2 = str_replace(' ', '_', $_GET["obj2"]);
	$obj3 = str_replace(' ', '_', $_GET["obj3"]);

	$_SESSION["obj1"] = $obj1;
	$_SESSION["obj2"] = $obj2;
	$_SESSION["obj3"] = $obj3;

	$objects0 = array(
		$obj1 => 0, 
		$obj2 => 0, 
		$obj3 => 0,
	);

	$objects1 = array(
		$obj1 => 0, 
		$obj2 => 0, 
		$obj3 => 0,
	);

	$objects2 = array(
		$obj1 => 0, 
		$obj2 => 0, 
		$obj3 => 0,
	);

	$objects_tr1 = array(
		$obj1 => 0, 
		$obj2 => 0, 
		$obj3 => 0,
	);

	$objects_tr2 = array(
		$obj1 => 0, 
		$obj2 => 0, 
		$obj3 => 0,
	);

	$_SESSION['objects_ts0'] = $objects0;
	$_SESSION['objects_ts1'] = $objects1;
	$_SESSION['objects_ts2'] = $objects2;
	$_SESSION['objects_tr1'] = $objects_tr1;
	$_SESSION['objects_tr2'] = $objects_tr2;

	// to make directories
	$url1_ts = $urlts . $obj1;
	$url2_ts = $urlts . $obj2;
	$url3_ts = $urlts . $obj3;

	$url1_tr1 = $urltr1 . $obj1;
	$url2_tr1 = $urltr1 . $obj2;
	$url3_tr1 = $urltr1 . $obj3;

	$url1_tr2 = $urltr2 . $obj1;
	$url2_tr2 = $urltr2 . $obj2;
	$url3_tr2 = $urltr2 . $obj3;

	$url1_ts1 = $urlts1 . $obj1;
	$url2_ts1 = $urlts1 . $obj2;
	$url3_ts1 = $urlts1 . $obj3;

	$url1_ts2 = $urlts2 . $obj1;
	$url2_ts2 = $urlts2 . $obj2;
	$url3_ts2 = $urlts2 . $obj3;

	// to make all necessary directories
	if (mkdir($url1_tr1, 0774, true) && mkdir($url2_tr1, 0774, true) &&
		mkdir($url3_tr1, 0774, true) && mkdir($url1_tr2, 0774, true) &&
		mkdir($url2_tr2, 0774, true) && mkdir($url3_tr2, 0774, true) &&
		mkdir($url1_ts1, 0774, true) && mkdir($url2_ts1, 0774, true) &&
		mkdir($url3_ts1, 0774, true) && mkdir($url1_ts2, 0774, true) &&
		mkdir($url2_ts2, 0774, true) && mkdir($url3_ts2, 0774, true) &&
		mkdir($url1_ts, 0774, true) && mkdir($url2_ts, 0774, true) &&
		mkdir($url3_ts, 0774, true))
	{
		echo("<script>console.log('Folders created');</script>");
	} else {
		echo("<script>console.log('Failed to create folder');</script>");
	}
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
			
			<p>Use your camera to take pictures of your objects in the next page. </p>
			
			<p>You will take an image with one of your objects at a time 
			and the object recognizer will give you the recognition result in response. You will take <strong>5 images</strong> for each object.</p>
		
			<p class="text-info">(Here's a hint: don't be scared if the object doesn't change! It's randomized, 
			so if you've clicked the next button and it doesn't change, take another picture.)</p>
			
			<p>Continue to find out!</p>

			<button type="button" class="btn btn-default" onclick="window.location.href='test0.php'">Next</button>
		

<?php
	include 'footer.php';
?>
