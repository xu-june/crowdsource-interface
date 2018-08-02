<?php
	include 'header.php';
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
		</script>
		<h3>Number your objects!</h3>
		<p>Assign your objects to Object 1, Object 2, and Object 3.</p>


		<form name="form" action="" method="get">
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
		
			<input type="submit" value="Submit" name="submit" id="submitbtn" >
			<!-- <p><button type="button" class="btn btn-primary" onclick="submit_objects()">Submit</button></p> -->
			<div>
				<?php 

				// Configuring errors
				// ini_set('display_errors',1);
				// error_reporting(E_ALL);
				// var_dump($_FILES); 

				session_start();

				// echo dirname(__FILE__) . "/images/";
                $img_base_dir = "images";
                $uuid = "12345"; // NOTE: for testing

				$urlts = dirname(__FILE__) . '/' . $img_base_dir . '/' . $uuid . "/test0/";
				$urltr1 = dirname(__FILE__) . '/' . $img_base_dir . '/' . $uuid . "/train1/";
				$urltr2 = dirname(__FILE__) . '/' . $img_base_dir . '/' . $uuid . "/train2/";
				$urlts1 = dirname(__FILE__) . '/' . $img_base_dir . '/' . $uuid . "/test1/";
				$urlts2 = dirname(__FILE__) . '/' . $img_base_dir . '/' . $uuid . "/test2/";

				// for randomization function in test phases
				$objects0 = array(
					$_GET["obj1"] => 0, 
					$_GET["obj2"] => 0, 
					$_GET["obj3"] => 0,
				);

				$objects1 = array(
					$_GET["obj1"] => 0, 
					$_GET["obj2"] => 0, 
					$_GET["obj3"] => 0,
				);

				$objects2 = array(
					$_GET["obj1"] => 0, 
					$_GET["obj2"] => 0, 
					$_GET["obj3"] => 0,
				);

				$objects_tr1 = array(
					$_GET["obj1"] => 0, 
					$_GET["obj2"] => 0, 
					$_GET["obj3"] => 0,
				);

				$objects_tr2 = array(
					$_GET["obj1"] => 0, 
					$_GET["obj2"] => 0, 
					$_GET["obj3"] => 0,
				);

				$_SESSION['objects_ts0'] = $objects0;
				$_SESSION['objects_ts1'] = $objects1;
				$_SESSION['objects_ts2'] = $objects2;
				$_SESSION['objects_tr1'] = $objects_tr1;
				$_SESSION['objects_tr2'] = $objects_tr2;

				// to make directories
				$url1_ts = $urlts . $_GET["obj1"];
				$url2_ts = $urlts . $_GET["obj2"];
				$url3_ts = $urlts . $_GET["obj3"];

				$url1_tr1 = $urltr1 . $_GET["obj1"];
				$url2_tr1 = $urltr1 . $_GET["obj2"];
				$url3_tr1 = $urltr1 . $_GET["obj3"];

				$url1_tr2 = $urltr2 . $_GET["obj1"];
				$url2_tr2 = $urltr2 . $_GET["obj2"];
				$url3_tr2 = $urltr2 . $_GET["obj3"];

				$url1_ts1 = $urlts1 . $_GET["obj1"];
				$url2_ts1 = $urlts1 . $_GET["obj2"];
				$url3_ts1 = $urlts1 . $_GET["obj3"];

				$url1_ts2 = $urlts2 . $_GET["obj1"];
				$url2_ts2 = $urlts2 . $_GET["obj2"];
				$url3_ts2 = $urlts2 . $_GET["obj3"];

                // to make all necessary directories
				// if (mkdir($url1_tr1, 0774, true) && mkdir($url2_tr1, 0774, true) &&
    //                 mkdir($url3_tr1, 0774, true) && mkdir($url1_tr2, 0774, true) &&
    //                 mkdir($url2_tr2, 0774, true) && mkdir($url3_tr2, 0774, true) &&
    //                 mkdir($url1_ts1, 0774, true) && mkdir($url2_ts1, 0774, true) &&
    //                 mkdir($url3_ts1, 0774, true) && mkdir($url1_ts2, 0774, true) &&
    //                 mkdir($url2_ts2, 0774, true) && mkdir($url3_ts2, 0774, true) &&
    //                 mkdir($url1_ts, 0774, true) && mkdir($url2_ts, 0774, true) &&
    //                 mkdir($url3_ts, 0774, true))
				// {
				//     echo("Folders created");
				// }

				?>
				
			</div>
		</form>

		<p>
			<button type="button"  class="btn btn-default" onclick="window.location.href='before_test0.php'">Next</button>
		</p>
        
	</div>
</body>
</html>