<?php
    session_start();
    include 'connectDB.php';
    include 'header.php';
	savePageLog($_SESSION['pid'], basename($_SERVER['PHP_SELF']));
    
    // update state
    $sql = "UPDATE variables set `phase`='before_training1', `upload_cnt_obj1`=0, `upload_cnt_obj2`=0, `upload_cnt_obj3`=0, `subset_cnt_obj`=0, `subset_cnt_num`=0  "
            ."where `participant_id`=".$_SESSION['pid']." and `trial`=".$_SESSION['trial'].";";
    execSQL($sql);
    
?>

<?php printProgressBar(8); ?>

<h4>Training Images</h4>
<p>Let’s see if you can train our object recognizer to identify correctly your objects. You can do this by providing many examples of how it looks.</p>

<p>We will randomly choose one of your objects and ask you to take 30 photos of it. 


<div align='right'>
    <button type="button" class="btn btn-primary" onclick="goToNext('train1');">Next ></button>
</div>