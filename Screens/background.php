<?php
	session_start();
	session_unset();
	include 'connectDB.php';
	include 'header.php';
	
	savePageLog(-1, "background");
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
		<div class="mt-3 mb-3 mr-3 ml-3">
  		<?php printProgressBar(1); ?>
  		
        <h3> Background Survey </h3><br>
        
        <form  action="tech_experience1.php" name="bgForm" method="post" id='backgroundForm'>
          <div class="form-group row">
            <label for="inputAge" class="col-2 col-form-label"><strong>Age</strong></label>
            <div class="col-10">
              <input type="number" name="age" required="true" class="form-control" id="inputAge" placeholder="Age">
            </div>
          </div><br>
          
          <!-- radio button set -->
          <fieldset class="form-group">
            <div class="row">
              <legend class="col-form-label col-2 pt-0"><strong>Gender</strong></legend>
              <div class="col-10">
                <div class="form-check">
                  <input class="form-check-input" required="true" type="radio" name="gender" id="genderMale" value="male" >
                  <label class="form-check-label" for="genderMale">
                    Male
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" required="true" type="radio" name="gender" id="genderFemale" value="female">
                  <label class="form-check-label" for="genderFemale">
                    Female
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" required="true" type="radio" name="gender" id="genderNB" value="nb">
                  <label class="form-check-label" for="genderNB">
                    Nonbinary (neither, both, or something else)
                  </label>
                </div>
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
                <div class="form-check">
                  <input class="form-check-input" required="true" type="radio" name="dom_hand" id="left_hand" value="left">
                  <label class="form-check-label" for="left_hand">
                    Left hand
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" required="true" type="radio" name="dom_hand" id="right_hand" value="right">
                  <label class="form-check-label" for="right_hand">
                    Right hand 
                  </label>
                </div>
              </div>
            </div>
          </fieldset><br>
          
          <!-- radio button set -->
          <fieldset class="form-group">
            <div>
              <legend class="col-form-label  pt-0"><strong>Do you have visual impairments?</strong></legend>
              <div>
              
                <div class="form-check">
                  <input class="form-check-input" onclick="vi_status(false);" required="true" type="radio" name="has_vi" id="vi_yes" value="yes">
                  <label class="form-check-label" onclick="vi_status(false);" required="true" for="vi_yes">
                    Yes
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" onclick="vi_status(true);" required="true" type="radio" name="has_vi" id="vi_no" value="no">
                  <label class="form-check-label" onclick="vi_status(true);" for="vi_no">
                    No
                  </label>
                </div>
                <label for="level_of_vision">Please describe your current level of vision. </label>
              	<textarea class="form-control" required="true" id="level_of_vision" name="level_of_vision" rows="1"></textarea>
                <label for="vi_age">For how many years have you had this level of vision ability? </label>
              	<textarea class="form-control" required="true" id="vi_years" name="vi_years" rows="1"></textarea>  
              </div>
            </div>
          </fieldset><br>
          
          <!-- radio button set -->
          <fieldset class="form-group">
            <div>
              <legend class="col-form-label  pt-0"><strong>Do you have motor impairments?</strong></legend>
              <div>
                <div class="form-check">
                  <input class="form-check-input" onclick="mi_status(false);" required="true" type="radio" name="has_mi" id="mi_yes" value="yes">
                  <label class="form-check-label" onclick="mi_status(false);" for="mi_yes">
                    Yes
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" onclick="mi_status(true);" required="true" type="radio" name="has_mi" id="mi_no" value="no">
                  <label class="form-check-label" onclick="mi_status(true);" for="mi_no">
                    No
                  </label>
                </div>
                <label for="motor_ability">Please describe your current motor ability. </label>
              	<textarea class="form-control" required="true" id="motor_ability" name="motor_ability" rows="1"></textarea>
                <label for="mi_age">For how many years have you had this level of motor ability? </label>
              	<textarea class="form-control" required="true" id="mi_years" name="mi_years" rows="1"></textarea>  
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
