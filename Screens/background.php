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
  </head>
  
	<body>
		<div class="mt-3 mb-3 mr-3 ml-3">
  		<?php printProgressBar(1); ?>
  		
        <h3> Background Survey </h3><br>
        
        <form  action="screen1_drink.php" method="post" id='backgroundForm'>
          <div class="form-group row">
            <label for="inputAge" class="col-2 col-form-label"><strong>Age</strong></label>
            <div class="col-10">
              <input type="text" name="age" class="form-control" id="inputAge" placeholder="Age">
            </div>
          </div><br>
          
          <!-- radio button set -->
          <fieldset class="form-group">
            <div class="row">
              <legend class="col-form-label col-2 pt-0"><strong>Gender</strong></legend>
              <div class="col-10">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="gender" id="genderMale" value="male">
                  <label class="form-check-label" for="genderMale">
                    Male
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="gender" id="genderFemale" value="female">
                  <label class="form-check-label" for="genderFemale">
                    Female
                  </label>
                </div>
              </div>
            </div>
          </fieldset><br>
          
          <!-- radio button set -->
          <fieldset class="form-group">
            <div>
              <legend class="col-form-label  pt-0"><strong>How do you know about object recognizer?</strong></legend>
              <div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="q1" id="q1a1" value="1">
                  <label class="form-check-label" for="q1a1">
                    No idea
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="q1" id="q1a2" value="2">
                  <label class="form-check-label" for="q1a2">
                    Have heard the name, but nothing more
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="q1" id="q1a3" value="3">
                  <label class="form-check-label" for="q1a3">
                    Know what it does
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="q1" id="q1a4" value="4">
                  <label class="form-check-label" for="q1a4">
                    Have studied object recognizer
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="q1" id="q1a5" value="5">
                  <label class="form-check-label" for="q1a5">
                    Have experience with project or work about object recognizer
                  </label>
                </div>
              </div>
            </div>
          </fieldset><br>
          
          <!-- radio button set -->
          <fieldset class="form-group">
            <div>
              <legend class="col-form-label  pt-0"><strong>How often do you use object recognizer?</strong></legend>
              <div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="q2" id="q2a1" value="1">
                  <label class="form-check-label" for="q2a1">
                    Less than once a month
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="q2" id="q2a2" value="2">
                  <label class="form-check-label" for="q2a2">
                    Once a month
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="q2" id="q2a3" value="3">
                  <label class="form-check-label" for="q2a3">
                    Several times a month
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="q2" id="q2a4" value="4">
                  <label class="form-check-label" for="q2a4">
                    Once a week
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="q2" id="q2a5" value="5">
                  <label class="form-check-label" for="q2a5">
                    Several times a week
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="q2" id="q2a6" value="5">
                  <label class="form-check-label" for="q2a6">
                    Once a day
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="q2" id="q2a7" value="5">
                  <label class="form-check-label" for="q2a7">
                    Several times a day
                  </label>
                </div>
              </div>
            </div>
          </fieldset><br>
          
          <!-- text form -->
          <div class="form-group">
            <label for="q5"><strong>What do you think the purpose of training is? What about testing?</strong></label>
            <textarea class="form-control" id="q3" name="q3" rows="3"></textarea>
          </div>
          
          <!-- text form -->
          <div class="form-group">
            <label for="q6"><strong>How do you think the images taken for training purposes and those taken for test purposes compare?</strong></label>
            <textarea class="form-control" id="q4" name="q4" rows="3"></textarea>
          </div>
          
          <button type="submit" class="btn btn-default">Next</button>
          
        </form>

<?php
	include 'footer.php';
?>