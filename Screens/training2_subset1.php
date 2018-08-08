<!-- CSS and jQuery adapted from http://www.prepbootstrap.com/bootstrap-template/image-checkbox -->
<!-- Takes images from train2 folder and saves selected filename into $scr9_subselect1 -->
<?php
	include 'header.php';
	if (!isset($_SESSION)) { 
		session_start(); 
	}

	// var_dump($_SESSION['curr']);

	$currObj = $_SESSION['curr'];
	$uuid = $_SESSION['pid'];
	array_shift($_SESSION['objects_tr2']);

	var_dump($_SESSION['subselectObj']);

	$hasNext = "true";
	if (count($_SESSION['objects_tr2']) == 0 || !isset($_SESSION['objects_tr2'])) {
		// echo "hello?";
		$hasNext = "false";
		// unset($_SESSION['subselectObj']);
	}

	$tr2_subselect5 = array();

	// Code from https://www.formget.com/php-checkbox/
	if(isset($_POST['selections']) && is_array($_POST['selections']))
	{ //to run PHP script on submit
		if(!empty($_POST['selections']))
		{
			// Copy each file name into $scr5_subselect20
			foreach($_POST['selections'] as $selected)
			{
				$tr2_subselect5[] = $selected;
			}
		}
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Subset Selection - 1</title>
	<?php printMetaInfo(); ?>

		<script type="text/javascript">

		// Limits images selected
		function limitCheck() {
        	var limit = 1;
        	var checked = 0;
        	var difference = 0;
        	var selections = document.getElementsByName('selections[]');
        	for (var i = 0; i < selections.length; i++) {
        		if (selections[i].checked) {
        			checked++;
        		}
        	}
        	if (checked > limit) {
        		difference = checked - limit;
        		var str = "images";
        		if (difference == 1) {
        			str = "image";
        		}
        		window.alert("Please deselect " + difference + " " + str);
        	}
        	else if (checked < limit) {
        		difference = limit - checked;
        		var str = "images";
        		if (difference == 1) {
        			str = "image";
        		}
        		window.alert("Please select " + difference + " more " + str);
        	}
        	else {
        		var hasNext = "<?php echo $hasNext ?>";
				if (hasNext == "true") {
					document.getElementById("selection").action = "training1_subset20.php";
				}
				else {
					document.getElementById("selection").action = "test1.php";
				}
        		document.getElementById("selection").submit();
        	}
        }

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
		<?php printProgressBar(8); ?>

		<h3>Select the best image out of the 5 you just chose of <?php echo $currObj ?>!</h3>

		<form id="selection" action="" onsubmit="next()" method="post">
			<p>A green border will appear around the images you select:</p>
				<?php
				$files = glob("images/p" . $uuid . "/t" . $_SESSION['trial'] ."/train2/" . $currObj . "/*.png");
				for ($i=0; $i<count($files); $i++)
				{
					$num = $files[$i];
					$filename = basename($num);
					// if $filename is contained in $scr5_subselect5, then display the picture
					if (in_array($filename, $tr2_subselect5)) {
						echo '<label class="image-checkbox">';
							echo '<img src="'.$num.'" style="width:150px; height:150px;" class="imgselect"/>'."&nbsp;&nbsp;";
							echo '<input type="checkbox" name="selections[]" value="' . $filename . '"/>';
						echo '</label>'; 
					}
				}

				?>
			<p><button id="submitbtn" type="button" onclick="limitCheck()" class="btn btn-default">Next</button></p>
		</form>
	</div>
</body>
</html>

<!-- <?php

// Configuring errors
// ini_set('display_errors',1);
// error_reporting(E_ALL);
// var_dump($_POST); 

// $tr2_subselect1 = array();

// // Code from https://www.formget.com/php-checkbox/
// if(isset($_POST['selections']) && is_array($_POST['selections']))
// { //to run PHP script on submit
// 	if(!empty($_POST['selections']))
// 	{
// 		// Copy each file name into $scr5_subselect20
// 		foreach($_POST['selections'] as $selected)
// 		{
// 			$tr2_subselect1[] = $selected;
// 		}
// 		// Display name of each file selected
// 		// foreach($scr9_subselect1 as $image)
// 		// {
// 		// 	echo $image."</br>";
// 		// }
// 	}

// 	$_SESSION['tr2_1'] = $tr2_subselect1;
// 	// if (empty($scr9_subselect20)) 
// 	// {
// 	// 	echo "array is empty";
// 	// }
// }
?>  