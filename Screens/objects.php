<?php
	session_start();
	include 'connectDB.php';
	include 'header.php';
	savePageLog($_SESSION['pid'], "objects");
?>

<!doctype html>
<html lang="en">
  <head>
  <?php printMetaInfo(); ?>
  <title>
    	Number your objects!
    </title>
  </head>
  
	<body>
		<div class="mt-3 mb-3 mr-3 ml-3">
  			<?php printProgressBar(2); ?>
		<script>
			function submit() {
				document.getElementById("submitbtn").click();
			}
			function submit_objects() {
				document.getElementById("submitbtn").click();
			} 
		</script>
		<h3>Number your objects!</h3>
		<p>Assign your objects to Object 1, Object 2, and Object 3.</p>


		<form name="form" action="before_test0.php" method="get">
			<div class="form-group row">
				<label for="obj1" class="col-3 col-form-label">Object 1</label>
				<div class="col-9">
				  <input type="text" name="obj1" class="form-control" id="obj1" placeholder="Name of object 1 (e.g., Coke)">
				</div>
			</div>
			<div class="form-group row">
				<label for="obj2" class="col-3 col-form-label">Object 2</label>
				<div class="col-9">
				  <input type="text" name="obj2" class="form-control" id="obj2" placeholder="Name of object 2 (e.g., Pepsi)">
				</div>
			</div>
			<div class="form-group row">
				<label for="obj3" class="col-3 col-form-label">Object 3</label>
				<div class="col-9">
				  <input type="text" name="obj3" class="form-control" id="obj3" placeholder="Name of object 3 (e.g., Sprite)">
				</div>
			</div>
		
<<<<<<< HEAD
			<input type="submit"  class="btn btn-default" value="Submit" name="submit" id="submitbtn">
			
			<!-- php code is moved to before_test0.php -->
			
		</form>
=======
			<!-- <input type="submit" value="Submit" name="submit" id="submitbtn" style="display: none;"> -->
			<p><button type="submit" class="btn btn-primary">Next</button></p>
		</form>

		<!-- <p><button type="button" class="btn btn-default" onclick="window.location.href='before_test0.php'">Next</button></p> -->
>>>>>>> b3ca12e2863e5326a8567b6675c39282e5ffb03c
        
<?php
	include 'footer.php';
?>