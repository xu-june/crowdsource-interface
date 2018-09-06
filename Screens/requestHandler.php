<?php
    session_start();
    include 'connectDB.php';
    include 'header.php';
    
    function submit_code() {
		if(!empty($_POST['code'])){
			$query = "SELECT participant_id, participant_code, trial FROM participant_status where status='COMPLETE' and participant_code='".$_POST['code']."';";
			$rows = getConn()->query($query);
		
			if ($rows->num_rows > 0) {
				$result = $rows->fetch_assoc();
				$pid = $result['participant_id'];
				$pcode = $result['participant_code'];
				$trial = $result['trial'];
				$date = date("Y-m-d H:i:s");
				$time = round(microtime(true) * 1000);
			
				$_SESSION['pid'] = $pid;
				$_SESSION['pcode'] = $pcode;
				$_SESSION['trial'] = $trial+1;
			
				// update participant status
				$sql = "UPDATE participant_status set `status`='IN PROGRESS', `trial`=".($trial+1).", `last_update_time`='"
					.$time."', `last_update_date`='".$date."'WHERE `participant_id`=".$pid.";";
				execSQL($sql);
			
				echo "success";
			} else {
				echo "invalid";
			}
		} else {
			echo "empty";
		}
    }
    
    function submit_selection() {
        $limit = $_POST['limit'];
        $img_num = $_POST['img_num'];
        $subset_cnt_obj = $_POST['subset_cnt_obj'];
        $subset_cnt_num = $_POST['subset_cnt_num'];
        $selected = $_POST['selected'];
        $subset_for = $_POST['subset_for'];
        
        /*
        	for debugging
        */
        if ($limit==6)
        	$limit = 20;
        
        $phase = 1;
        if ($subset_for == 'train2')
        	$phase = 2;
        
        $obj_index = $_SESSION['order'][$subset_for][$subset_cnt_obj];
        $obj_name = $_SESSION['object_names'][$obj_index-1];
        
        //echo implode(",", $_SESSION['order'][$subset_for])."-".$limit;
        
		$sql = "UPDATE Objects set `subset".$phase."_".$obj_index."_".$limit."`='".$selected."'"
			." WHERE `participant_id`=".$_SESSION['pid']." and `trial`=".$_SESSION['trial'].";";
		execSQL($sql);
		
		if ($img_num == 30) {
			$subset_cnt_num = 20;
		} else if ($img_num == 20){
			$subset_cnt_num = 5;
		} else if ($img_num == 5){
			$subset_cnt_obj += 1;
			$subset_cnt_num = 0;
		}
		
		if ($subset_cnt_obj == 3) {
			if ($phase == 1) {
                $curr_obj_index = getObjectIndex('train2', 0);
                $objectname = $_SESSION["object_names"][$curr_obj_index-1];
                echo "before_training2=0=0=0=0=0=".$objectname."=".$curr_obj_index."=";
			} else {
				echo "post_questions=0=0=0=0=0=na=0=";
            }
	        
		} else {
			$obj_index = $_SESSION['order'][$subset_for][$subset_cnt_obj];
			$obj_name = $_SESSION['object_names'][$obj_index-1];
			
			$sql = "UPDATE variables set `subset_cnt_obj`=".$subset_cnt_obj.", `subset_cnt_num`=".$subset_cnt_num." "
                ."where `participant_id`=".$_SESSION['pid']." and `trial`=".$_SESSION['trial'].";";
	        execSQL($sql);
	        
	        echo "subset_train".$phase."=0=0=0=".$subset_cnt_obj."=".$subset_cnt_num."=".$obj_name."=".$obj_index."=";
		}
    }
    
    function update_phase() {
        // update state
        $next = $_POST['phase'];
        $sql = "UPDATE variables set `phase`='".$next."', `upload_cnt_obj1`=0, `upload_cnt_obj2`=0, `upload_cnt_obj3`=0, `subset_cnt_obj`=0, `subset_cnt_num`=0 "
                ."where `participant_id`=".$_SESSION['pid']." and `trial`=".$_SESSION['trial'].";";
        execSQL($sql);
        
        $curr_obj_index = getObjectIndex($next, 0);
        $objectname = $_SESSION["object_names"][$curr_obj_index-1];
        
        // write action log
        $date = date("Y-m-d H:i:s");
        $time = round(microtime(true) * 1000);
        $sql = "INSERT INTO action_logs "
                ."(`participant_id`, `trial`, `event`, `time`, `date`) VALUES ("
                .$_SESSION['pid'].",".$_SESSION['trial'].", 'goToNext-".$next . "', '".$time."', '".$date."');";
        execSQL($sql);
        
        // return next state
        echo $next."=0=0=0=0=0=".$objectname."=".$curr_obj_index."=";
        
        //start training if the phase is subset selection
        /*
        if (strpos($next, 'subset') == 0) {
        	$subset_for = 'train1';
        	if (strpos($next, 'train1') === false) {
				$subset_for = 'train2';
			}
			training_start($subset_for);
        }
        */
    }
    
    function submit_feedback1() {
        //delete existing feedback info
        $sql = "UPDATE feedback set `f1q1`='".str_replace("'", "", $_POST['q1'])."', `f1q2`='".str_replace("'", "", $_POST['q2'])."', `f1q3`='".str_replace("'", "", $_POST['q3'])."' where `participant_id`=".$_SESSION['pid']." and `trial`=".$_SESSION['trial'].";";
		execSQL($sql);
        
        $sql = "UPDATE variables set `phase`='subset_train1', `upload_cnt_obj1`=0, `upload_cnt_obj2`=0, `upload_cnt_obj3`=0, `subset_cnt_obj`=0, `subset_cnt_num`=0 "
                ."where `participant_id`=".$_SESSION['pid']." and `trial`=".$_SESSION['trial'].";";
		execSQL($sql);
        
        $obj_index = $_SESSION['order']['train1'][0];
        $obj_name = $_SESSION['object_names'][$obj_index - 1];
        
        echo "subset_train1=0=0=0=0=0=".$obj_name."=".$obj_index."=";
    }
    
    function submit_feedback2() {
        //delete existing feedback info
        $sql = "UPDATE feedback set `f2q1`='".str_replace("'", "", $_POST['q1'])."', `f2q2`='".str_replace("'", "", $_POST['q2'])."' where `participant_id`=".$_SESSION['pid']." and `trial`=".$_SESSION['trial'].";";
		execSQL($sql);
        
        $sql = "UPDATE variables set `phase`='subset_train2', `upload_cnt_obj1`=0, `upload_cnt_obj2`=0, `upload_cnt_obj3`=0, `subset_cnt_obj`=0, `subset_cnt_num`=0 "
                ."where `participant_id`=".$_SESSION['pid']." and `trial`=".$_SESSION['trial'].";";
		execSQL($sql);
        
        $obj_index = $_SESSION['order']['train2'][0];
        $obj_name = $_SESSION['object_names'][$obj_index - 1];
        
        echo "subset_train2=0=0=0=0=0=".$obj_name."=".$obj_index."=".$_POST["f2q1"];
    }
    
    function submit_trq($index) {
        //delete existing feedback info
        $sql = "UPDATE feedback set `tr".$index."q1`='".str_replace("'", "", $_POST['q1'])."', `tr".$index."q2`='".str_replace("'", "", $_POST['q2'])."', `tr".$index."q3`='".str_replace("'", "", $_POST['q3'])."' where `participant_id`=".$_SESSION['pid']." and `trial`=".$_SESSION['trial'].";";
		execSQL($sql);
        
        echo "before_test".$index."=0=0=0=0=0=na=0=";
    }
    
    function submit_screen_size() {
        $sql = "UPDATE feedback set `screen_size`='".$_POST['screen_size']."' where `participant_id`=".$_SESSION['pid']." and `trial`=".$_SESSION['trial'].";";
		execSQL($sql);
    }
    
    // write action log
    $date = date("Y-m-d H:i:s");
    $time = round(microtime(true) * 1000);
    $sql = "INSERT INTO action_logs "
            ."(`participant_id`, `trial`, `event`, `time`, `date`) VALUES ("
            .$_SESSION['pid'].",".$_SESSION['trial'].", 'request-".$_POST['type']."', '".$time."', '".$date."');";
    execSQL($sql);
    
    if ($_POST['type']=='submit_code'){
    	submit_code();
    } else if ($_POST['type']=='submit_background'){
    	submit_background();
    } else if ($_POST['type']=='update_phase'){
        update_phase();
    } else if ($_POST['type']=='submit_selection'){
        submit_selection();
    } else if ($_POST['type']=='submit_feedback1'){
        submit_feedback1();
    } else if ($_POST['type']=='submit_feedback2'){
        submit_feedback2();
    } else if ($_POST['type']=='submit_trq1'){
        submit_trq(1);
    } else if ($_POST['type']=='submit_trq2'){
        submit_trq(2);
    } else if ($_POST['type']=='submit_screen_size'){
        submit_screen_size();
    }  
?>