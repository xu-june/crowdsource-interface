<?php
	session_start();
	include 'connectDB.php';
	include 'header.php';
	savePageLog($_SESSION['pid'], basename($_SERVER['PHP_SELF']));

	$q1 = $_POST["bq1"];
	$q2 = $_POST["bq2"];
	$q3 = $_POST["bq3"];
	$sql = "UPDATE participant_info set `bq1`='".$q1."', `bq2`='".$q2."', `bq3`='".$q3."' WHERE `participant_id`=".$_SESSION['pid'].";";
	execSQL($sql);
?> 

<!doctype html>
<html lang="en">
<head>
  <?php printMetaInfo(); ?>
  <title>
   Technology Experience Questions
 </title>


</head>
  
	<body>
		<div class="mt-3 mb-3 mr-3 ml-3">
  		<?php printProgressBar(3); ?>
  		
        <h3> Technology Experience Questions </h3>
        
        <form  action="screen1_objects.php" name="bgForm" method="post" id='backgroundForm'>
          
          <!-- radio button set -->
          <fieldset class="form-group">
            <div>
              <legend class="col-form-label  pt-0"><strong>What do you believe artificial intelligence is?</strong></legend>
              <div>
                <div class="form-check">
                  <input class="form-check-input" required="true" type="radio" name="bq4" id="q4a1" value="1">
                  <label class="form-check-label" required="true" for="q4a1">
                    <p class='radio_font'>Putting your intelligence into a smart machine and robot</p>
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" required="true" type="radio" name="bq4" id="q4a2" value="2">
                  <label class="form-check-label" required="true" for="q4a2">
                    <p class='radio_font'>Programming a smart machine and robot with your own intelligence</p>
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" required="true" type="radio" name="bq4" id="q4a3" value="3">
                  <label class="form-check-label" required="true" for="q4a3">
                    <p class='radio_font'>Making a smart machine and robot intelligent</p>
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" required="true" type="radio" name="bq4" id="q4a4" value="4">
                  <label class="form-check-label" required="true" for="q4a4">
                    <p class='radio_font'>Putting more memory into a smart machine or robot</p>
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" required="true" type="radio" name="bq4" id="q4a5" value="5">
                  <label class="form-check-label" required="true" for="q4a5">
                    <p class='radio_font'>Playing a game</p>
                  </label>
                </div>
              </div>
            </div>
          </fieldset><br>
          
          <!-- radio button set -->
          <fieldset class="form-group">
            <div>
              <legend class="col-form-label  pt-0"><strong>How would you classify your level of familiarity with machine learning?</strong></legend>
              <div>
                <div class="form-check">
                  <input class="form-check-input" required="true" type="radio" name="bq5" id="q5a1" value="1">
                  <label class="form-check-label" required="true" for="q5a1">
                    <p class='radio_font'>Not familiar at all (have never heard of machine learning) </p>
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" required="true" type="radio" name="bq5" id="q5a2" value="2">
                  <label class="form-check-label" required="true" for="q5a2">
                    <p class='radio_font'>Slightly familiar (have heard of it but don’t know what it does)</p>
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" required="true" type="radio" name="bq5" id="q5a3" value="3">
                  <label class="form-check-label" required="true" for="q5a3">
                    <p class='radio_font'>Somewhat familiar (I have a broad understanding of what it is and what it does)</p>
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" required="true" type="radio" name="bq5" id="q5a4" value="4">
                  <label class="form-check-label" required="true" for="q5a4">
                    <p class='radio_font'>Extremely familiar (I have extensive knowledge on machine learning.)</p>
                  </label>
                </div>
              </div>
            </div>
          </fieldset><br>
          
          
          <!-- text form -->
          <div class="form-group">
            <label for="occupation"><strong>How would you train your phone to recognize your facial expressions (e.g., happy, sad, calm, angry)? </strong></label>
            <textarea class="form-control" required="true" id="occupation" name="bq6" rows="3"></textarea>
          </div><br>
          
          <!-- text form -->
          <div class="form-group">
            <label for="occupation"><strong>How would you test if it works well?  </strong></label>
            <textarea class="form-control" required="true" id="occupation" name="bq7" rows="3"></textarea>
          </div><br>
          
          <div align='right'>
	          <button type="submit" class="btn btn-primary">Next ></button>
          </div>
          
        </form>

<?php
	include 'footer.php';
?>
