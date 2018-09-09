<?php
    session_start();
    include 'connectDB.php';
    include 'header.php';
	savePageLog($_SESSION['pid'], basename($_SERVER['PHP_SELF']));
    
    // update state
    $sql = "UPDATE variables set `phase`='before_test1', `upload_cnt_obj1`=0, `upload_cnt_obj2`=0, `upload_cnt_obj3`=0, `subset_cnt_obj`=0, `subset_cnt_num`=0  "
            ."where `participant_id`=".$_SESSION['pid']." and `trial`=".$_SESSION['trial'].";";
    execSQL($sql);
    
?>

<?php printProgressBar(11); ?>

<h4>Test</h4>

<p>Test the trained object recognizer again to see how robust it is. </p>

<div align='right'>
    <!--<button type="button" class="btn btn-default" onclick="window.location.href='test0.php'">Next ></button>-->
    <button type="button" class="btn btn-primary" onclick="goToNext('test1');">Next</button>
</div>
		