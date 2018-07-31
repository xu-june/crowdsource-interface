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

    <title>Questions</title>
  </head>
  <body>
    <div class="mt-3 mb-3 mr-3 ml-3">
        <div class="progress mb-3">
          <div class="progress-bar" role="progressbar" style="width: 10%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">9 / 10</div>
        </div>
        
        <h3> Post-study Questions </h3><br>
        
        <form  action="end.php">
          <!-- text form -->
          <div class="form-group">
            <label for="q1"><strong>How did you position the object in the image?</strong></label>
            <textarea class="form-control" id="q1" rows="3"></textarea>
          </div>
          
          <!-- text form -->
          <div class="form-group">
            <label for="q2"><strong>How did you decide the distance of the camera from the object?</strong></label>
            <textarea class="form-control" id="q2" rows="3"></textarea>
          </div>
          
          <!-- text form -->
          <div class="form-group">
            <label for="q3"><strong>How did you decide which side of the object is visible in the image?</strong></label>
            <textarea class="form-control" id="q3" rows="3"></textarea>
          </div>
          
          <!-- text form -->
          <div class="form-group">
            <label for="q4"><strong>Did you have anything else in mind while taking pictures?</strong></label>
            <textarea class="form-control" id="q4" rows="3"></textarea>
          </div>
          
          <button type="submit" class="btn btn-default">Next</button>
          
        </form>
    </div> 
  </body>
</html>