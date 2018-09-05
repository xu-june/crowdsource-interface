<?php
	session_start();
	include 'connectDB.php';
	include 'header.php';
		
	savePageLog(-1, basename($_SERVER['PHP_SELF']));
?> 
<!doctype html>
<html lang="en">
<head>
  <?php printMetaInfo(); ?>
  <title>
   Background Survey
 </title>

	<script type="text/javascript">
	  
	  function vi_status(status){
		document.getElementById("level_of_vision").disabled = status;
		document.getElementById("vi_years").disabled = status;
	  }
	  
	  function mi_status(status){
		document.getElementById("motor_ability").disabled = status;
		document.getElementById("mi_years").disabled = status;
	  }
 	</script>

</head>
  
	<body>
		<div class="mt-3 mb-5 mr-3 ml-3">
  		<?php 
            printProgressBar(1); 
            
            if (!empty($_POST['code'])) {
                $query = "SELECT participant_id, participant_code, trial FROM participant_status where status='COMPLETE' and participant_code='".$_POST['code']."';";
                
                //echo $query."<br>";
                $rows = getConn()->query($query);
                
                if ($rows->num_rows > 0) {
                    $result = $rows->fetch_assoc();
                    $pid = $result['participant_id'];
                    $pcode = $result['participant_code'];
                    $trial = $result['trial'];
                    $date = date("Y-m-d H:i:s");
                    $time = round(microtime(true) * 1000);
                    
                    if ($trial == 5) {
                        echo "<p>A participant is allowed to participate 5 times at most. You already participated 5 times.<br> Thank you!</p>";
                        echo "<p>(You will not be compensated if you participate again without the code or with another code.)</p>";
                        exit();
                    }
                    
                    $_SESSION['pid'] = $pid;
                    $_SESSION['pcode'] = $pcode;
                    $_SESSION['trial'] = $trial+1;
                    
                    // update participant status
                    $sql = "UPDATE participant_status set `status`='IN PROGRESS', `trial`=".($trial+1).", `last_update_time`='"
                        .$time."', `last_update_date`='".$date."'WHERE `participant_id`=".$pid.";";
                    execSQL($sql);
                    
                    header("Location: screen1_objects.php"); /* Redirect browser */
                    exit();
                } else {
                    echo "Your code is invalid. <br>Start a new study if you have never completed this study before. Otherwise, enter the correct code.";
                    exit();
                }
            }
        ?>
  		
        <h4> Background Survey </h4><br>
        
        <form  action="tech_experience1.php" name="bgForm" method="post" id='backgroundForm'>
          <div class="form-group row">
            <label for="inputAge" class="col-2 col-form-label"><strong>Age</strong></label>
            <div class="col-10">
              <input type="number" name="age" required="true" class="form-control" id="inputAge" placeholder="Age">
            </div>
          </div><br>
          
          <!-- radio button set -->
          <fieldset class="form-group">
              <legend class="col-form-label pt-0"><strong>Gender </strong></legend>
              <div>
                <div class="form-check mb-2">
                  <input class="form-check-input" required="true" type="radio" name="gender" id="genderMale" value="male" >
                  <label class="form-check-label" for="genderMale">
                    <p class='radio_font'>Male</p>
                  </label>
                </div>
                <div class="form-check mb-2">
                  <input class="form-check-input" required="true" type="radio" name="gender" id="genderFemale" value="female">
                  <label class="form-check-label" for="genderFemale">
						<p class='radio_font'>Female</p>
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" required="true" type="radio" name="gender" id="genderNB" value="nb">
                  <label class="form-check-label" for="genderNB">
                    <p class='radio_font'>Nonbinary (neither, both, or something else)</p>
                  </label>
                </div>
              </div>
          </fieldset><br>
          
          <!-- text form -->
          <div class="form-group">
            <label for="occupation"><strong>What is your occupation? (If student, what do you study?)</strong></label>
            <textarea class="form-control" required="true" id="occupation" name="occupation" rows="1"></textarea>
          </div><br>
          
          <!-- radio button set -->
          <fieldset class="form-group">
            <div>
              <legend class="col-form-label  pt-0"><strong>What is your dominant hand?</strong></legend>
              <div>
                <div class="form-check mb-2">
                  <input class="form-check-input" required="true" type="radio" name="dom_hand" id="left_hand" value="left">
                  <label class="form-check-label" for="left_hand">
                    <p class='radio_font'>Left hand</p>
                  </label>
                </div>
                <div class="form-check mb-2">
                  <input class="form-check-input" required="true" type="radio" name="dom_hand" id="right_hand" value="right">
                  <label class="form-check-label" for="right_hand">
                    <p class='radio_font'>Right hand </p>
                  </label>
                </div>
              </div>
            </div>
          </fieldset><br>
          
          <!-- radio button set -->
          <fieldset class="form-group">
            <div>
              <legend class="col-form-label pt-0"><strong>Do you have visual impairments?</strong></legend>
              <div>
              
                <div class="form-check mb-2">
                  <input class="form-check-input" onclick="vi_status(false);" required="true" type="radio" name="has_vi" id="vi_yes" value="yes">
                  <label class="form-check-label" onclick="vi_status(false);" required="true" for="vi_yes">
                    <p class='radio_font'>Yes</p>
                  </label>
                </div>
                <div class="form-check mb-2">
                  <input class="form-check-input" onclick="vi_status(true);" required="true" type="radio" name="has_vi" id="vi_no" value="no">
                  <label class="form-check-label" onclick="vi_status(true);" for="vi_no">
                    <p class='radio_font'>No</p>
                  </label>
                </div>
                <label for="level_of_vision">Please describe your current level of vision. </label>
              	<textarea class="form-control" required="true" id="level_of_vision" name="level_of_vision" rows="1"></textarea>
                <label for="vi_years">For how many years have you had this level of vision ability? </label>
              	<textarea class="form-control" required="true" id="vi_years" name="vi_years" rows="1"></textarea>  
              </div>
            </div>
          </fieldset><br>
          
          <!-- radio button set -->
          <fieldset class="form-group">
            <div>
              <legend class="col-form-label pt-0"><strong>Do you have motor impairments?</strong></legend>
              <div>
                <div class="form-check mb-2">
                  <input class="form-check-input" onclick="mi_status(false);" required="true" type="radio" name="has_mi" id="mi_yes" value="yes">
                  <label class="form-check-label" onclick="mi_status(false);" for="mi_yes">
                    <p class='radio_font'>Yes</p>
                  </label>
                </div>
                <div class="form-check mb-2">
                  <input class="form-check-input" onclick="mi_status(true);" required="true" type="radio" name="has_mi" id="mi_no" value="no">
                  <label class="form-check-label" onclick="mi_status(true);" for="mi_no">
                    <p class='radio_font'>No</p>
                  </label>
                </div>
                <label for="motor_ability">Please describe your current motor ability. </label>
              	<textarea class="form-control" required="true" id="motor_ability" name="motor_ability" rows="1"></textarea>
                <label for="mi_years">For how many years have you had this level of motor ability? </label>
              	<textarea class="form-control" required="true" id="mi_years" name="mi_years" rows="1"></textarea>  
              </div>
            </div>
          </fieldset><br>
          
          <div align='right'>
	          <button type="submit" class="btn btn-primary">Next</button>
          </div>
          
        </form>

<br><br><br> <!-- space at the bottom -->

		</div>
	</body>
</html>
