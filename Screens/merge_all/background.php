<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <script src="./js/jquery-3.3.1.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>

    <title>Background Survey</title>
  </head>
  <body>
    <div class="mt-3 mb-3 mr-3 ml-3">
        <div class="progress mb-3">
          <div class="progress-bar" role="progressbar" style="width: 14%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">1 / 7</div>
        </div>
        
        <h2> Background Survey </h2><br>
        
        <form  action="enter_objects.php">
          <div class="form-group row">
            <label for="inputAge" class="col-2 col-form-label"><strong>Age</strong></label>
            <div class="col-10">
              <input type="age" class="form-control" id="inputAge" placeholder="Age">
            </div>
          </div><br>
          
          <!-- radio button set -->
          <fieldset class="form-group">
            <div class="row">
              <legend class="col-form-label col-2 pt-0"><strong>Gender</strong></legend>
              <div class="col-10">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="gridRadios" id="genderMale" value="male">
                  <label class="form-check-label" for="genderMale">
                    Male
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="gridRadios" id="genderFemale" value="female">
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
                  <input class="form-check-input" type="radio" name="q3" id="q3a1" value="1">
                  <label class="form-check-label" for="q3a1">
                    No idea
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="q3" id="q3a2" value="2">
                  <label class="form-check-label" for="q3a2">
                    Have heard the name, but nothing more
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="q3" id="q3a3" value="3">
                  <label class="form-check-label" for="q3a3">
                    Know what it does
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="q3" id="q3a4" value="4">
                  <label class="form-check-label" for="q3a4">
                    Have studied object recognizer
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="q3" id="q3a5" value="5">
                  <label class="form-check-label" for="q3a5">
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
                  <input class="form-check-input" type="radio" name="q4" id="q4a1" value="1">
                  <label class="form-check-label" for="q4a1">
                    Less than once a month
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="q4" id="q4a2" value="2">
                  <label class="form-check-label" for="q4a2">
                    Once a month
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="q4" id="q4a3" value="3">
                  <label class="form-check-label" for="q4a3">
                    Several times a month
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="q4" id="q4a4" value="4">
                  <label class="form-check-label" for="q4a4">
                    Once a week
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="q4" id="q4a5" value="5">
                  <label class="form-check-label" for="q4a5">
                    Several times a week
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="q4" id="q4a6" value="5">
                  <label class="form-check-label" for="q4a6">
                    Once a day
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="q4" id="q4a7" value="5">
                  <label class="form-check-label" for="q4a7">
                    Several times a day
                  </label>
                </div>
              </div>
            </div>
          </fieldset><br>
          
          <!-- text form -->
          <div class="form-group">
            <label for="q5"><strong>What do you think the purpose of training is? What about testing?</strong></label>
            <textarea class="form-control" id="q5" rows="3"></textarea>
          </div>
          
          <!-- text form -->
          <div class="form-group">
            <label for="q6"><strong>How do you think the images taken for training purposes and those taken for test purposes compare?</strong></label>
            <textarea class="form-control" id="q6" rows="3"></textarea>
          </div>
          
          <button type="submit" class="btn btn-primary">Next</button>
          
        </form>
    </div> 
  </body>
</html>