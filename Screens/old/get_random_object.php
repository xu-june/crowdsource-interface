<?php
    session_start();
	$phase = $_POST['phase'];
	
	if (strpos($phase, 'test') === 0) { 
		if ($_SESSION['step'] >= 3*$_SESSION['test_img_num']) {
			echo 'this step is done';
		} else {
			if ($phase == 'test0') {
				$order = $_SESSION['test0_order'];
			} else if ($phase == 'test1') {
				$order = $_SESSION['test1_order'];
			} else if ($phase == 'test2') {
				$order = $_SESSION['test2_order'];
			}
			$count = $_SESSION['step'];
			$obj_index = $order[$count]-1;
			$obj = $_SESSION['object_names'][$obj_index];
			echo $obj.' '.$count;
		}
	} else {
		if ($_SESSION['step'] >= 3*$_SESSION['training_img_num']) {
			echo 'this step is done';
		} else {
			$count = $_SESSION['step'] % 30;
			$obj_index = $order[$_SESSION['step']/30];
			$obj = $_SESSION['object_names'][$obj_index];
			echo $obj.' '.$count;
		}
	}
?>