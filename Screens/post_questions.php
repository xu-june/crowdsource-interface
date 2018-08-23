<?php 
    session_start();
	include 'header.php';
    include 'connectDB.php';
    savePageLog($_SESSION['pid'], "post_questions");
    
    
    $f2q1 = $_POST["f2q1"];
	$sql = "UPDATE participant_info set `f2q1`='".$f2q1."' WHERE `participant_id`=".$_SESSION['pid'].";";
	execSQL($sql);
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <?php printMetaInfo(); ?>

    <title>Post-study Questions</title>
  </head>
  <body>
    <div class="mt-3 mb-3 mr-3 ml-3">
        <?php printProgressBar(18); ?>
        
        <h4> Post-study Questions </h4><br>
        
        <form  action="end.php">
          <!-- text form -->
          <div class="form-group">
            <label for="q1"><strong>How did you position the object in the image?</strong></label>
            <textarea class="form-control" required="true" id="pq1" rows="3"></textarea>
          </div>
          
          <!-- text form -->
          <div class="form-group">
            <label for="q2"><strong>How did you decide the distance of the camera from the object?</strong></label>
            <textarea class="form-control" required="true" id="pq2" rows="3"></textarea>
          </div>
          
          <!-- text form -->
          <div class="form-group">
            <label for="q3"><strong>How did you decide which side of the object is visible in the image?</strong></label>
            <textarea class="form-control" required="true" id="pq3" rows="3"></textarea>
          </div>
          
          <!-- text form -->
          <div class="form-group">
            <label for="q4"><strong>Did you have anything else in mind while taking pictures?</strong></label>
            <textarea class="form-control" required="true" id="pq4" rows="3"></textarea>
          </div>
          
          <div align="right">
            <button type="submit" class="btn btn-default">Next ></button>
          </div>
        </form>
<?php
	include 'footer.php';
?>