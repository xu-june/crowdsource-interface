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
			<table>
				<tr>
					<td width="20%">
						<img src="images/can_green.png" width="100%">
					</td>
					<td>
						<div><label for="obj1">Object 1: </label></div>
						<input type="text" required="true" name="obj1" required="true" class="form-control" id="obj1" placeholder="Name of object 1 (e.g., Coke)">
					</td>
				</tr>
			</table>
			<table>
				<tr>
					<td width="20%">
						<img src="images/can_orange.png" width="100%">
					</td>
					<td>
						<div><label for="obj2">Object 2: </label></div>
						<input type="text" required="true" name="obj2" required="true" class="form-control" id="obj2" placeholder="Name of object 2 (e.g., Pepsi)">
					</td>
				</tr>
			</table>
			<table>
				<tr>
					<td width="20%">
						<img src="images/can_blue.png" width="100%">
					</td>
					<td>
						<div><label for="obj3">Object 3: </label></div>
						<input type="text" required="true" name="obj3" required="true" class="form-control" id="obj3" placeholder="Name of object 3 (e.g., Sprite)">
					</td>
				</tr>
			</table>
			
			<div align='right'>
				<input type="submit"  class="btn btn-default" value="Next >" name="submit" id="submitbtn">
			</div>
			
			<!-- php code is moved to before_test0.php -->
			
		</form>
        
<?php
	include 'footer.php';
?>