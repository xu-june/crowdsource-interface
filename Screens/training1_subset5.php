<!-- CSS and jQuery adapted from http://www.prepbootstrap.com/bootstrap-template/image-checkbox -->
<!-- Takes images from train1 folder and saves selected filenames into $scr5_subselect5 -->
<?php
	include 'header.php';
	if (!isset($_SESSION)) { 
		session_start(); 
	}

	var_dump($_SESSION['curr']);
	var_dump($_POST['selections']);

	$currObj = $_SESSION['curr'];

	$tr1_subselect20 = array();
	// Code from https://www.formget.com/php-checkbox/
	if(isset($_POST['selections']) && is_array($_POST['selections']))
	{ //to run PHP script on submit
		if(!empty($_POST['selections']))
		{
			// Copy each file name into $scr5_subselect20
			foreach($_POST['selections'] as $selected)
			{
				$tr1_subselect20[] = $selected;
			}
			// Display name of each file selected
			// foreach($scr5_subselect20 as $image)
			// {
			// 	echo $image."</br>";
			// }
		}

		// $_SESSION['tr1_20'] = $tr1_subselect20;
		// if (empty($scr5_subselect20)) 
		// {
		// 	echo "array is empty";
		// }
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Subset selection - 5</title>
	<?php printMetaInfo(); ?>

	<script type="text/javascript">
		// function next() {
		// 	document.getElementById("selection").submit();
		// 	window.location.href='training1_subset1.php';
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

	<h3>Select the 5 best images out of the 20 you just chose!</h3>

	<!-- Form redirects to subset selection - 1 -->
	<form id="selection" action="training1_subset1.php" method="post">
		<p>A green border will appear around the images you select:</p>
		<!-- <div id = "subselection"> -->
			<?php
			// session_start();

			// $tr1_subselect20 = $_SESSION['tr1_20'];	// Retrieve file names selected previously
			// Displays the images
			$files = glob("images/12345/train1/".$currObj."/*.jpg");
			for ($i=0; $i<count($files); $i++)
			{
				$num = $files[$i];
				$filename = basename($num);
				// if $filename is contained in $scr5_subselect20, then display the picture
				if (in_array($filename, $tr1_subselect20)) {
					echo '<label class="image-checkbox">';
						echo '<img src="'.$num.'" style="width:150px; height:150px;" class="imgselect"/>'."&nbsp;&nbsp;";
						echo '<input type="checkbox" name="selections[]" value="' . $filename . '" />';
					echo '</label>'; 
				}
			}
			?>
		<!-- </div> -->
		<!-- <input type="submit" id="uploadbtn" value="Upload Image" name="submit" style="display: none;"> -->
        <!-- <p><button type="button" onclick="uploadImg()">Done!</button></p> -->
        <p><button type="submit" class="btn btn-default">Next</button></p>
	</form>

	</div>
</body>
</html>