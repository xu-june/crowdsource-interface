<?php
	session_start();
    include 'connectDB.php';
    include 'header.php';
	savePageLog($_SESSION['pid'], basename($_SERVER['PHP_SELF']));
    
    // update state
    $sql = "UPDATE variables set `phase`='before_training2', `upload_cnt_obj1`=0, `upload_cnt_obj2`=0, `upload_cnt_obj3`=0, `subset_cnt_obj`=0, `subset_cnt_num`=0 "
            ."where `participant_id`=".$_SESSION['pid']." and `trial`=".$_SESSION['trial'].";";
    execSQL($sql);
?>

<?php printProgressBar(13); ?>

<h3>You told us what you would do differently, now show us!</h3>
<p>On the next screen, take 30 more pictures of the requested object.</p>

<div align="right">
    <button type="button" class="btn btn-primary" onclick="goToNext('train2');">Next ></button>
</div>
