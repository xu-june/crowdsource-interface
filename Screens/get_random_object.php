<?php

    session_start();
	$phase = $_POST['phase'];

	// Gets array of objects and counts
	$objects_key = '';
	if ($phase == 'test0') {
		$objects_key = 'objects_ts0';
	} else if ($phase == 'test1') {
		$objects_key = 'objects_ts1';
	} else if ($phase == 'test2') {
		$objects_key = 'objects_ts2';
	} else if ($phase == 'traini1') {
		$objects_key = 'objects_tr1';
	} else if ($phase == 'train1') {
		$objects_key = 'objects_tr2';
	}
	$objects = $_SESSION[$objects_key];

	$obj1 = key($objects);
	$_SESSION['obj1'] = $obj1;
	next($objects);
	$obj2 = key($objects);
	$_SESSION['obj2'] = $obj2;
	next($objects);
	$obj3 = key($objects);
	$_SESSION['obj3'] = $obj3;
	reset($objects); 

	$randObj = "";
	$limit = 5; // temporary value for debugging
	if (strpos($phase, 'test') === 0) {
		$limit = 1;
		//$limit = 1; // temporary value for debugging
	}
	
	// Ensures that this executes until all objects have been shown 5 times
	if ($_SESSION[$objects_key][$obj1] < $limit || $_SESSION[$objects_key][$obj2] < $limit || $_SESSION[$objects_key][$obj3] < $limit) {
		$randObj = array_rand($objects, 1);
		// Ensures each object is called 5 times
		while ($objects[$randObj] >= $limit) {
			$randObj = array_rand($objects, 1);
		}
		// Sends object to upload file
		$_SESSION['currObj'] = $randObj;
		echo $randObj.' '.($objects[$randObj]+1);
	} else {
		echo 'this step is done';
	}
?>