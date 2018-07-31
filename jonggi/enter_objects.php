<?php
	include 'header.php';
	printHeader('Background', 1);
?>
       
        <div>
            <h3> Enter names of three objects </h3>
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
        
        
<?php
	include('footer.php');
?>