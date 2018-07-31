<!-- CSS and jQuery adapted from http://www.prepbootstrap.com/bootstrap-template/image-checkbox -->
<!-- Takes images from train1 folder and saves selected filename into $scr5_subselect1 -->
<?php
	include 'header.php';
?>

<!DOCTYPE html>
<html>
<head>
	<title>Subset selection - 1</title>
	<?php printMetaInfo(); ?>

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
	<div class="mt-3 mb-3 mr-3 ml-3">
		<?php printProgressBar(5); ?>

		<h3>Select the best image out of the 5 you just chose!</h3>

		<form action="" method="post">
			<p>A green border will appear around the images you select:</p>
			<div id = "subselection">
				<?php
				session_start();
				$scr5_subselect5 = $_SESSION['scr5_5'];
				// Displays the images
				$files = glob("train1/*.jpg");
				for ($i=0; $i<count($files); $i++)
				{
					$num = $files[$i];
					$filename = basename($num);
					// if $filename is contained in $scr5_subselect5, then display the picture
					if (in_array($filename, $scr5_subselect5)) {
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
			<button type="button" class="btn btn-default" onclick="window.location.href='test2.php'">Next</button>
		</p>
	</div>
</body>
</html>

<?php 

// Configuring errors
// ini_set('display_errors',1);
// error_reporting(E_ALL);
// var_dump($_POST); 

session_start();

$scr5_subselect1 = array();

// Code from https://www.formget.com/php-checkbox/
if(isset($_POST['selections']) && is_array($_POST['selections']))
{ //to run PHP script on submit
	if(!empty($_POST['selections']))
	{
		// Copy each file name into $scr5_subselect20
		foreach($_POST['selections'] as $selected)
		{
			$scr5_subselect1[] = $selected;
		}
		// Display name of each file selected
		// foreach($scr5_subselect1 as $image)
		// {
		// 	echo $image."</br>";
		// }
	}

	$_SESSION['scr5_1'] = $scr5_subselect1;
	// if (empty($scr5_subselect20)) 
	// {
	// 	echo "array is empty";
	// }
}
?>