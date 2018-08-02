<!-- CSS and jQuery adapted from http://www.prepbootstrap.com/bootstrap-template/image-checkbox -->
<!-- Takes images from train1 folder and saves selected filename into $scr5_subselect1 -->
<?php
	include 'header.php';
	if (!isset($_SESSION)) { 
		session_start(); 
	}

	if (empty($_SESSION['subselectObj'])) {
		header("Location: /test1.php"); 
	}
	else {
		header("Location: /training1_subset20.php");
	}

	var_dump($_SESSION['curr']);
	var_dump($_SESSION['subselectObj']);

	$currObj = $_SESSION['curr'];
	array_shift($_SESSION['subselectObj']);		// Shifts first element out of array
	$hasNext = true;
	if (empty($_SESSION['subselectObj'])) {
		$hasNext = false;
		unset($_SESSION['subselectObj']);
	}

	$tr1_subselect5 = array();

	// Code from https://www.formget.com/php-checkbox/
	if(isset($_POST['selections']) && is_array($_POST['selections']))
	{ //to run PHP script on submit
		if(!empty($_POST['selections']))
		{
			// Copy each file name into $scr5_subselect20
			foreach($_POST['selections'] as $selected)
			{
				$tr1_subselect5[] = $selected;
			}
			// Display name of each file selected
			// foreach($scr5_subselect5 as $image)
			// {
			// 	echo $image."</br>";
			// }
		}

		// $_SESSION['tr1_5'] = $tr1_subselect5;
		// if (empty($scr5_subselect20)) 
		// {
		// 	echo "array is empty";
		// }
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Subset selection - 1</title>
	<?php printMetaInfo(); ?>

	<script type="text/javascript">
		// If there are no more elements in the array of objects, page redirects to test1 phase; else goes back to subset selection
		// function next() {
		// 	var hasNext = <?= $hasNext ?>;
		// 	if (!hasNext) {
		// 		window.location.href='test1.php';
		// 	}
		// 	else {
		// 		window.location.href='training1_subset20.php';
		// 	}
		// }

		function uploadImg() {
        	document.getElementById("uploadbtn").click();
        }

		jQuery(function ($) {
			$(".image-checkbox").on("click", function (e) {
				// if checked, uncheck it
				if ($(this).hasClass('image-checkbox-checked')) {
					$(this).removeClass('image-checkbox-checked');
					$(this).find('input[type="checkbox"]').removeAttr("checked");
				}
				else {
					$(this).addClass('image-checkbox-checked');
					$(this).find('input[type="checkbox"]').attr("checked", "checked");
				}

				e.preventDefault();
			});
		});
	</script>

	<style type="text/css">
		/*For subset selection*/
		.image-checkbox
		{
			cursor: pointer;
		    box-sizing: border-box;
		    -moz-box-sizing: border-box;
		    -webkit-box-sizing: border-box;
		    outline: 0;
		}

		.image-checkbox input[type="checkbox"]
		{
			display: none;
		}

		.image-checkbox-checked
		{
			outline: 4px solid #33cc33;
		}

		.imgselect {
			padding: 5px;
		}
	</style>
</head>
<body>
	<div class="mt-3 mb-3 mr-3 ml-3">
		<?php printProgressBar(5); ?>

		<h3>Select the best image out of the 5 you just chose!</h3>

		<form id="selection" action="" method="post">
			<p>A green border will appear around the images you select:</p>
			<!-- <div id = "subselection"> -->
				<?php
				// session_start();
				// $tr1_subselect5 = $_SESSION['tr1_5'];
				// Displays the images
				$files = glob("images/12345/train1/".$currObj."/*.jpg");
				for ($i=0; $i<count($files); $i++)
				{
					$num = $files[$i];
					$filename = basename($num);
					// if $filename is contained in $scr5_subselect5, then display the picture
					if (in_array($filename, $tr1_subselect5)) {
						echo '<label class="image-checkbox">';
							echo '<img src="'.$num.'" style="width:150px; height:150px;" class="imgselect"/>'."&nbsp;&nbsp;";
							echo '<input type="checkbox" name="selections[]" value="' . $filename . '"/>';
						echo '</label>'; 
					}
				}


				?>
			<!-- </div> -->
			<!-- <input type="submit" id="uploadbtn" value="Done!" name="submit""> -->
			<!-- <p><button type="button" onclick="uploadImg()">Done!</button></p> -->
			<p><button type="submit" class="btn btn-default">Next</button></p>
		</form>

	</div>
</body>
</html>

<!-- <?php 

// Configuring errors
// ini_set('display_errors',1);
// error_reporting(E_ALL);
// var_dump($_POST); 

// $tr1_subselect1 = array();

// // Code from https://www.formget.com/php-checkbox/
// if(isset($_POST['selections']) && is_array($_POST['selections']))
// { //to run PHP script on submit
// 	if(!empty($_POST['selections']))
// 	{
// 		// Copy each file name into $scr5_subselect20
// 		foreach($_POST['selections'] as $selected)
// 		{
// 			$tr1_subselect1[] = $selected;
// 		}
// 		// Display name of each file selected
// 		// foreach($scr5_subselect1 as $image)
// 		// {
// 		// 	echo $image."</br>";
// 		// }
// 	}

// 	$_SESSION['tr1_1'] = $tr1_subselect1;
// 	// if (empty($scr5_subselect20)) 
// 	// {
// 	// 	echo "array is empty";
// 	// }
// }
?>  -->