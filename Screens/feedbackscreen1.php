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

<?php printProgressBar(13); ?>

<form name="feedback" action="" method="post" id="feedbackForm">
    <h4>Questions</h4>
    
    <!-- radio button set -->
      <fieldset class="form-group">
          <legend class="col-form-label pt-0"><strong>The performance of the object recognizer was as good as I expected. </strong></legend>
          <div>
            <div class="form-check mb-1">
              <input class="form-check-input" required="true" type="radio" name="q1" id="q1_1" value="1" >
              <label class="form-check-label" for="q1_1">
                <p class='radio_font'>Strongly disagree</p>
              </label>
            </div>
            <div class="form-check mb-1">
              <input class="form-check-input" required="true" type="radio" name="q1" id="q1_2" value="2">
              <label class="form-check-label" for="q1_2">
                <p class='radio_font'>Disagree</p>
              </label>
            </div>
            <div class="form-check mb-1">
              <input class="form-check-input" required="true" type="radio" name="q1" id="q1_3" value="3">
              <label class="form-check-label" for="q1_3">
                <p class='radio_font'>Neutral</p>
              </label>
            </div>
            <div class="form-check mb-1">
              <input class="form-check-input" required="true" type="radio" name="q1" id="q1_4" value="4">
              <label class="form-check-label" for="q1_4">
                <p class='radio_font'>Agree</p>
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" required="true" type="radio" name="q1" id="q1_5" value="5">
              <label class="form-check-label" for="q1_5">
                <p class='radio_font'>Strongly agree</p>
              </label>
            </div>
          </div>
      </fieldset>
    Why?
    <textarea name="q2" required="true" class="form-control" id="q2" rows="3" placeholder="Enter feedback here"></textarea>
    <br>

    <p><strong>If you were to retrain the system to make it more robust, what would you do differently? </strong></p>
    <textarea name="q3" required="true" id="q3" class="form-control" rows="3" placeholder="Enter feedback here"></textarea>
    <br>

    <div align="right">
        <button type="button" class="btn btn-primary" onclick="submit_feedback1()">Next</button>
        <button type="submit" id='submit_button' class="btn btn-primary" style='display:none;'>Next</button>
    </div>
</form>
		
