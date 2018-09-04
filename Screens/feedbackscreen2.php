<?php
    session_start();
    include 'connectDB.php';
    include 'header.php';
	savePageLog($_SESSION['pid'], basename($_SERVER['PHP_SELF']));
    
    // update state
    $sql = "UPDATE variables set `phase`='feedbackscreen2', `upload_cnt_obj1`=0, `upload_cnt_obj2`=0, `upload_cnt_obj3`=0 "
            ."where `participant_id`=".$_SESSION['pid']." and `trial`=".$_SESSION['trial'].";";
    execSQL($sql);
?>

<script>
    function countChar(val) {
        var length = val.value.length;
        if (length >= 800) {
          val.value = val.value.substring(0, 800);
        } else {
          $('#charCount').text(800 - length);
        }
      };
      
</script>

<?php printProgressBar(22); ?>

<h4>Compare the result to your expectations!</h4>
<p>Did you expect to get this result? Or did you expect the accuracy to be higher/lower?</p>
<p>Please tell us what you expected and why, as well as your result and why you think you got this result.</p>

<form name="feedback" action="post_questions.php" method="post" id="feedbackForm">
    <textarea name="f2q1" id = "f2q1" required="true" class="form-control" rows="3" onkeyup="countChar(this)" placeholder="Enter feedback here"></textarea>
    <div class="chcount"><small><span id="charCount">800</span>/800 characters left</small></div>
    <br>

    <div align="right">
        <button type="button" class="btn btn-primary" onclick="submit_feedback2()">Next ></button>
        <button type="submit" id='submit_button' class="btn btn-primary" style='display:none;'>Next ></button>
    </div>
</form>