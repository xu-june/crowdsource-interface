<!-- CSS and jQuery adapted from http://www.prepbootstrap.com/bootstrap-template/image-checkbox -->
<!-- Takes images from train2 folder and saves selected filenames into $scr9_subselect5 -->

<!DOCTYPE html>
<html>
<head>
	<title>Draft of Screen 9</title>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
	<link type="text/css" rel="stylesheet" href="screenformatting.css">

	<script type="text/javascript">
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
</head>
<body>
	<div class="grid-container">
        <div class="grid-item">Initial Testing</div>
        <div class="grid-item">Training 1</div>
        <div class="grid-item">Testing 1</div>
        <div id="curr-screen">Training 2</div>  
        <div class="grid-item">Testing 2</div>
    </div>

	<h1>Select the 5 best images out of the 20 you just chose!</h1>

	<form action="" method="post">
		<p>A green border will appear around the images you select:</p>
		<div id = "subselection">
			<?php
			session_start();

			$scr9_subselect20 = $_SESSION['scr9_20'];	// Retrieve file names selected previously
			// Displays the images
			$files = glob("train2/*.jpg");
			for ($i=0; $i<count($files); $i++)
			{
				$num = $files[$i];
				$filename = basename($num);
				// if $filename is contained in $scr9_subselect20, then display the picture
				if (in_array($filename, $scr9_subselect20)) {
					echo '<label class="image-checkbox">';
						echo '<img src="'.$num.'" style="width:150px; height:150px;" class="imgselect"/>'."&nbsp;&nbsp;";
						echo '<input type="checkbox" name="selections[]" value="' . $filename . '" />';
					echo '</label>'; 
				}
			}
			?>
		</div>
		<input type="submit" id="uploadbtn" value="Upload Image" name="submit" style="display: none;">
        <p><button type="button" onclick="uploadImg()">Done!</button></p>
	</form>

	<p>
		<button type="button" onclick="window.location.href='screen9_subselect1.php'">Next</button>
	</p>

</body>
</html>

<?php 

// Configuring errors
// ini_set('display_errors',1);
// error_reporting(E_ALL);
// var_dump($_POST); 

session_start();

$scr9_subselect5 = array();

// Code from https://www.formget.com/php-checkbox/
if(isset($_POST['selections']) && is_array($_POST['selections']))
{ //to run PHP script on submit
	if(!empty($_POST['selections']))
	{
		// Copy each file name into $scr5_subselect20
		foreach($_POST['selections'] as $selected)
		{
			$scr9_subselect5[] = $selected;
		}
		// Display name of each file selected
		// foreach($scr9_subselect5 as $image)
		// {
		// 	echo $image."</br>";
		// }
	}

	$_SESSION['scr9_5'] = $scr9_subselect5;
	// if (empty($scr5_subselect20)) 
	// {
	// 	echo "array is empty";
	// }
}
?>