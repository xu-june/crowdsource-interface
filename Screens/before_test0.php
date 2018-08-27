<?php
	session_start();
	include 'connectDB.php';
	include 'header.php';
	savePageLog($_SESSION['pid'], basename($_SERVER['PHP_SELF']));
	
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
	$uuid = $_SESSION['pid']; 

	// init the object recognizer
	require(dirname(__FILE__).'/../TOR/rest_client.php');
	$puuid = 'p' . $uuid;
	init_recognizer($puuid);

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
	$_SESSION["object_names"] = array($obj1, $obj2, $obj3);
	
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
	
	
	// set order of trainig and testing
	$test0_order = array();
	$test1_order = array();
	$test2_order = array();
	$train1_order = array();
	$train2_order = array();
	
	for ($i=0; $i<$_SESSION['test_img_num']; $i++) {
		array_push($test0_order, 1, 2, 3);
		array_push($test1_order, 1, 2, 3);
		array_push($test2_order, 1, 2, 3);
	}
	array_push($train1_order, 1, 2, 3);
	array_push($train2_order, 1, 2, 3);
	
	shuffle($test0_order);
	shuffle($test1_order);
	shuffle($test2_order);
	shuffle($train1_order);
	shuffle($train2_order);
	
	$_SESSION['order']['test0'] = $test0_order;
	$_SESSION['order']['test1'] = $test1_order;
	$_SESSION['order']['test2'] = $test2_order;
	$_SESSION['order']['train1'] = $train1_order;
	$_SESSION['order']['train2'] = $train2_order;
	
	$sql = "delete from Objects where `participant_id`=".$uuid." and `trial`=".$_SESSION['trial'].";";
	execSQL($sql);
	
	$date = date("Y-m-d H:i:s");
	$time = round(microtime(true) * 1000);
	$sql = "INSERT INTO Objects "
			."(`participant_id`, `trial`, `category`, `name1`, `name2`, `name3`, `test0_order`, `test1_order`, `test2_order`, `train1_order`, `train2_order`, `add_time`, `add_date`) VALUES ("
			.$uuid.",".$_SESSION['trial'].", '".$_SESSION['current_category'].", '".$obj1."', '".$obj2."', '".$obj3."', '".implode(':',$test0_order)."', '"
            .implode(':',$test1_order)."', '".implode(':',$test2_order)."', '".implode(':',$train1_order)."', '".implode(':',$train2_order)."', '".$time."', '".$date."');";
	execSQL($sql);
?>

<!doctype html>
<html lang="en">
  <head>
  <?php printMetaInfo(); ?>
  <title>
    	Before test 1
    </title>
  </head>
  
	<body>
		<div class="mt-3 mb-3 mr-3 ml-3">
	  		<?php printProgressBar(6); ?>
       
			<h4>Testing images</h4>
			
			<p>We will randomly choose one of your objects and ask you to take a photo of it. </p>
			
			<p>You will do this 15 times total (5 photos per object). </p>
			
			<p> <strong>Hint</strong>: We have already trained an object recognizer but chances are it does not include your objects. 
			Every time you take a photo it will try to guess what it is based on what it knows. </p>
			
			<div align='right'>
				<button type="button" class="btn btn-default" onclick="window.location.href='test0.php'">Next ></button>
			</div>
		

<?php
	include 'footer.php';
?>
