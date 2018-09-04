<?php 
    session_start();
	include 'header.php';
    include 'connectDB.php';
	savePageLog($_SESSION['pid'], basename($_SERVER['PHP_SELF']));
  
    // update state
    $sql = "UPDATE variables set `phase`='post_questions', `upload_cnt_obj1`=0, `upload_cnt_obj2`=0, `upload_cnt_obj3`=0, `subset_cnt_obj`=0, `subset_cnt_num`=0 "
            ."where `participant_id`=".$_SESSION['pid']." and `trial`=".$_SESSION['trial'].";";
    execSQL($sql);

	$uuid = $_SESSION['pid'];

	// stop the object recognizer
	require(dirname(__FILE__).'/../TOR/rest_client.php');
	$puuid = 'p' . $uuid;
	stop_recognizer($puuid);
?>

        <?php printProgressBar(26); ?>
        
        <h4>Questions </h4><br>
        <p>Please answer the questions about how you took photos for training and testing.</p>
        
        <form  action="end.php" method="post">
          <!-- text form -->
          <div class="form-group">
            <label for="q1"><strong>How did you position the object in the image?</strong></label>
            <textarea class="form-control" required="true" id="pq1" name='pq1' rows="3"></textarea>
          </div>
          
          <!-- text form -->
          <div class="form-group">
            <label for="q2"><strong>How did you decide the distance of the camera from the object?</strong></label>
            <textarea class="form-control" required="true" id="pq2" name='pq2' rows="3"></textarea>
          </div>
          
          <!-- text form -->
          <div class="form-group">
            <label for="q3"><strong>How did you decide which side of the object is visible in the image?</strong></label>
            <textarea class="form-control" required="true" id="pq3" name='pq3' rows="3"></textarea>
          </div>
          
          <!-- text form -->
          <div class="form-group">
            <label for="q4"><strong>Did you have anything else in mind while taking pictures?</strong></label>
            <textarea class="form-control" required="true" id="pq4" name='pq4' rows="3"></textarea>
          </div>
          
          <div align="right">
            <button type="submit" class="btn btn-primary">Next ></button>
          </div>
        </form>
        