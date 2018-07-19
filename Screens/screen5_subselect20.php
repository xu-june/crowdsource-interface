<!-- CSS and jQuery adapted from http://www.prepbootstrap.com/bootstrap-template/image-checkbox -->
<!-- Takes images from train1 folder and puts them into subset1/select20 -->

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

	<h1>Select the 20 best images out of the 30 you took!</h1>

	<div id = "subselection">
		<?php
		// Displays the images
		$files = glob("train1/*.jpg");
		for ($i=0; $i<count($files); $i++)
		{
			$num = $files[$i];
			echo '<label class="image-checkbox">';
				echo '<img src="'.$num.'" style="width:150px; height:150px;" class="imgselect"/>'."&nbsp;&nbsp;";
				echo '<input type="checkbox" name="team[]" />';
			echo '</label>';
			
		}
		?>
	</div>

	<p>
		<button type="button" onclick="window.location.href='http://ec2-18-221-159-134.us-east-2.compute.amazonaws.com/Screens/screen5_subselect5.php'">Next</button>
	</p>

</body>
</html>