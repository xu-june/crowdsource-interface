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

<?php printProgressBar(13); ?>

<h4>Now, can our system tell which is which?</h4>

<p>Continue to find out! </p>

<p>Test the trained object recognizer again. </p> 
<p>Take a photo of an object (name at the top) by tapping in the camera screen. The recognition result will be shown at the bottom. </p>

<p>You will do this 15 times total (5 photos per object). </p>

<div align='right'>
    <!--<button type="button" class="btn btn-default" onclick="window.location.href='test0.php'">Next ></button>-->
    <button type="button" class="btn btn-default" onclick="goToNext('test1');">Next ></button>
</div>
		