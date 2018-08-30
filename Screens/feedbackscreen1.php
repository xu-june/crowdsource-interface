<?php
    session_start();
    include 'connectDB.php';
    include 'header.php';
	savePageLog($_SESSION['pid'], basename($_SERVER['PHP_SELF']));
    
    // update state
    $sql = "UPDATE variables set `phase`='feedbackscreen1', `upload_cnt_obj1`=0, `upload_cnt_obj2`=0, `upload_cnt_obj3`=0 "
            ."where `participant_id`=".$_SESSION['pid']." and `trial`=".$_SESSION['trial'].";";
    execSQL($sql);
?>

<script>
    function countChar1(val) {
        var length = val.value.length;
        if (length >= 800) {
          val.value = val.value.substring(0, 800);
        } else {
          $('#charCount1').text(800 - length);
        }
      };
      function countChar2(val) {
        var length = val.value.length;
        if (length >= 800) {
          val.value = val.value.substring(0, 800);
        } else {
          $('#charCount2').text(800 - length);
        }
      };
      
</script>

<?php printProgressBar(12); ?>

<form name="feedback" action="" method="post" id="feedbackForm">
    <h4>Compare the result to your expectations!</h4>
    <p>Did you expect to get this result? Or did you expect the accuracy to be higher/lower?</p>
    <p>Please tell us what you expected and why, as well as your result and why you think you got this result.</p>
    <textarea name="f1q1" required="true" class="form-control" id="f1q1" rows="3" onkeyup="countChar1(this)" placeholder="Enter feedback here"></textarea>
    <div class="chcount"><small><span id="charCount1">800</span>/800 characters left</small></div>
    <br>

    <p>If you were to retrain the system, what would you do differently? Think in terms of photo style, angle, lighting, etc.</p>
    <textarea name="f1q2" required="true" id="f1q2" class="form-control" rows="3" onkeyup="countChar2(this)" placeholder="Enter feedback here"></textarea>
    <div class="chcount"><small><span id="charCount2">800</span>/800 characters left</small></div>


    <div align="right">
        <button type="button" class="btn btn-primary" onclick="submit_feedback('submit_feedback1')">Next ></button>
        <button type="submit" id='submit_button' class="btn btn-primary" style='display:none;'>Next ></button>
    </div>
</form>
		
