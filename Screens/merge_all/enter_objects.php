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

    <title>Enter names of objects</title>
  </head>
  <body>
    <div class="mt-3 mb-3 mr-3 ml-3">
        <div class="progress mb-3">
          <div class="progress-bar" role="progressbar" style="width: 28%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">2 / 7</div>
        </div>
        
        <div>
            <h2> Enter names of three objects </h2>
        </div>
        <div>
            <small> Before continuing, please choose 3 distinct canned drinks to use! Here are some ideas: </small>
        </div>
        <br>
        
        <center>
            <img src="images/coke.jpg" class="img-responsive" style="width:25%">
            <img src="images/pepsi.jpg" class="img-responsive" style="width:25%">
            <img src="images/sprite.jpg" class="img-responsive" style="width:25%">
        </center>
        <br>
        
        <form  action="test1.php">
          <div class="form-group row">
            <label for="obj1" class="col-3 col-form-label">Object 1</label>
            <div class="col-9">
              <input type="name1" class="form-control" id="obj1" placeholder="name">
            </div>
          </div>
          
          <div class="form-group row">
            <label for="obj2" class="col-3 col-form-label">Object 2</label>
            <div class="col-9">
              <input type="name2" class="form-control" id="obj2" placeholder="name">
            </div>
          </div>
          
          <div class="form-group row">
            <label for="obj3" class="col-3 col-form-label">Object 3</label>
            <div class="col-9">
              <input type="name3" class="form-control" id="obj3" placeholder="name">
            </div>
          </div>
          
          <button type="submit" class="btn btn-primary">Next</button>
          
        </form>
    </div> 
  </body>
</html>