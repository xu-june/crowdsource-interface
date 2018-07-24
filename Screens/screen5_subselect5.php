<!-- CSS and jQuery adapted from http://www.prepbootstrap.com/bootstrap-template/image-checkbox -->
<!-- Takes images from train1 folder and saves selected filenames into $scr5_subselect5 -->

<!DOCTYPE html>
<html>
<head>
	<title>Draft of Screen 5</title>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
	<link type="text/css" rel="stylesheet" href="screenformatting.css">

	<script type="text/javascript">

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
        <div id="curr-screen">Training 1</div>
        <div class="grid-item">Testing 1</div>
        <div class="grid-item">Training 2</div>  
        <div class="grid-item">Testing 2</div>
    </div>

	<h1>Select the 5 best images out of the 20 you just chose!</h1>

	<form action="screen5_sub5.php" method="post">
		<p>A green border will appear around the images you select:</p>
		<div id = "subselection">
			<?php
			include("screen5_sub20.php");
			// Displays the images
			$files = glob("train1/*.jpg");
			for ($i=0; $i<count($files); $i++)
			{
				$num = $files[$i];
				$filename = basename($num);
				// if $filename is contained in $scr5_subselect20, then display the picture
				if (in_array($filename, $scr5_subselect20)) {
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
		<button type="button" onclick="window.location.href='http://ec2-18-221-159-134.us-east-2.compute.amazonaws.com/Screens/screen5_subselect1.php'">Next</button>
	</p>

</body>
</html>