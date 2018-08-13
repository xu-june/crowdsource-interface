<?php
	session_unset();
	session_start();
	include 'connectDB.php';
	include 'header.php';

	// get participant id	
	if (!isset($_SESSION['pid'])) {
		$query = "SELECT participant_id, participant_code, trial FROM participant_status where status='INCOMPLETE' order by participant_id";
		$result = getSelect($query);
		$pid = $result['participant_id'];
		$pcode = $result['participant_code'];
		$trial = $result['trial'];
		$date = date("Y-m-d H:i:s");
		$time = round(microtime(true) * 1000);

		// enter participant info	
		$age = $_POST["age"];
		$gender = $_POST["gender"];
		$occupation = $_POST["occupation"];
		$dom_hand = $_POST["dom_hand"];
		$has_vi = $_POST["has_vi"];
		$level_of_vision = $_POST["level_of_vision"];
		$vi_years = $_POST["vi_years"];
		$has_mi = $_POST["has_mi"];
		$motor_ability = $_POST["motor_ability"];
		$mi_years = $_POST["mi_years"];
		$sql = "INSERT INTO participant_info "
			."(`participant_id`, `age`, `gender`, `occupation`, `dom_hand`, `has_vi`, `level_of_vision`, `vi_years`, `has_mi`, `motor_ability`, `mi_years`, `time`, `date`) VALUES ("
			.$pid.",".$age.", '".$gender."', '".$occupation."', '".$dom_hand."', '".$has_vi."', '".$level_of_vision."','".$vi_years."','".$has_mi."','".$motor_ability."','".$mi_years
			."','".$time."','".$date."');";
			
		execSQL($sql);
	
		// update participant status
		$sql = "UPDATE participant_status set `status`='IN PROGRESS', `trial`=".($trial+1).", `last_update_time`='"
			.$time."', `last_update_date`='".$date."'WHERE `participant_id`=".$pid.";";
		execSQL($sql);
	
		// save page log and session variables
		$_SESSION['pid'] = $pid;
		$_SESSION['pcode'] = $pcode;
		$_SESSION['trial'] = 1;
		$_SESSION['test_img_num'] = 5;  // temporary value for debugging
		$_SESSION['training_img_num'] = 30; // temporary value for debugging
	}
	savePageLog($_SESSION['pid'], "tech_experience1");
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
  		<?php printProgressBar(2); ?>
  		
        <h3> Technology Experience Questions </h3>
        
        <p>Please indicate how often do you do the following.</p>
        <form  action="tech_experience2.php" name="bgForm" method="post" id='backgroundForm'>
          
          <!-- radio button set -->
          <fieldset class="form-group">
            <div>
              <legend class="col-form-label  pt-0"><strong>Use a mobile device.</strong></legend>
              <div>
                <div class="form-check">
                  <input class="form-check-input" required="true" type="radio" name="bq1" id="q1a1" value="1">
                  <label class="form-check-label" required="true" for="q1a1">
                    Never
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" required="true" type="radio" name="bq1" id="q1a2" value="2">
                  <label class="form-check-label" required="true" for="q1a2">
                    Once a month
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" required="true" type="radio" name="bq1" id="q1a3" value="3">
                  <label class="form-check-label" required="true" for="q1a3">
                    Several times a month
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" required="true" type="radio" name="bq1" id="q1a4" value="4">
                  <label class="form-check-label" required="true" for="q1a4">
                    Once a week
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" required="true" type="radio" name="bq1" id="q1a5" value="5">
                  <label class="form-check-label" required="true" for="q1a5">
                    Several times a week
                  </label>
                </div>
				<div class="form-check">
				  <input class="form-check-input" required="true" type="radio" name="bq1" id="q1a6" value="6">
				  <label class="form-check-label" required="true" for="q1a6">
					Once a day
				  </label>
					</div>
				<div class="form-check">
				  <input class="form-check-input" required="true" type="radio" name="bq1" id="q1a7" value="7">
				  <label class="form-check-label" required="true" for="q1a7">
					Several times a day
				  </label>
				</div>
              </div>
            </div>
          </fieldset><br>
          
          <!-- radio button set -->
          <fieldset class="form-group">
            <div>
              <legend class="col-form-label  pt-0"><strong>Take pictures using a mobile phone</strong></legend>
              <div>
                <div class="form-check">
                  <input class="form-check-input" required="true" type="radio" name="bq2" id="q2a1" value="1">
                  <label class="form-check-label" required="true" for="q2a1">
                    Never
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" required="true" type="radio" name="bq2" id="q2a2" value="2">
                  <label class="form-check-label" required="true" for="q2a2">
                    Once a month
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" required="true" type="radio" name="bq2" id="q2a3" value="3">
                  <label class="form-check-label" required="true" for="q2a3">
                    Several times a month
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" required="true" type="radio" name="bq2" id="q2a4" value="4">
                  <label class="form-check-label" required="true" for="q2a4">
                    Once a week
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" required="true" type="radio" name="bq2" id="q2a5" value="5">
                  <label class="form-check-label" required="true" for="q2a5">
                    Several times a week
                  </label>
                </div>
				<div class="form-check">
				  <input class="form-check-input" required="true" type="radio" name="bq2" id="q2a6" value="6">
				  <label class="form-check-label" required="true" for="q2a6">
					Once a day
				  </label>
					</div>
				<div class="form-check">
				  <input class="form-check-input" required="true" type="radio" name="bq2" id="q2a7" value="7">
				  <label class="form-check-label" required="true" for="q2a7">
					Several times a day
				  </label>
				</div>
              </div>
            </div>
          </fieldset><br>
          
          <!-- radio button set -->
          <fieldset class="form-group">
            <div>
              <legend class="col-form-label  pt-0"><strong>Use apps that can recognize type of objects, food, or plants through the camera. </strong></legend>
              <div>
                <div class="form-check">
                  <input class="form-check-input" required="true" type="radio" name="bq3" id="q3a1" value="1">
                  <label class="form-check-label" required="true" for="q3a1">
                    Never
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" required="true" type="radio" name="bq3" id="q3a2" value="2">
                  <label class="form-check-label" required="true" for="q3a2">
                    Once a month
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" required="true" type="radio" name="bq3" id="q3a3" value="3">
                  <label class="form-check-label" required="true" for="q3a3">
                    Several times a month
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" required="true" type="radio" name="bq3" id="q3a4" value="4">
                  <label class="form-check-label" required="true" for="q3a4">
                    Once a week
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" required="true" type="radio" name="bq3" id="q3a5" value="5">
                  <label class="form-check-label" required="true" for="q3a5">
                    Several times a week
                  </label>
                </div>
				<div class="form-check">
				  <input class="form-check-input" required="true" type="radio" name="bq3" id="q3a6" value="6">
				  <label class="form-check-label" required="true" for="q3a6">
					Once a day
				  </label>
					</div>
				<div class="form-check">
				  <input class="form-check-input" required="true" type="radio" name="bq3" id="q3a7" value="7">
				  <label class="form-check-label" required="true" for="q3a7">
					Several times a day
				  </label>
				</div>
              </div>
            </div>
          </fieldset><br>
          
          <div align='right'>
	          <button type="submit" class="btn btn-default">Next ></button>
          </div>
          
        </form>

<?php
	include 'footer.php';
?>
