<!-- CSS and jQuery adapted from http://www.prepbootstrap.com/bootstrap-template/image-checkbox -->
<!-- Takes images from train1 folder and saves selected filename into $scr5_subselect1 -->
<?php
    session_start();
	include 'header.php';
    include 'connectDB.php';
    savePageLog($_SESSION['pid'], "train1_subset1");

	// var_dump($_SESSION['curr']);

	$currObj = $_SESSION['curr'];
	$obj = str_replace('_', ' ', $currObj);
	$uuid = $_SESSION['pid'];
	array_shift($_SESSION['objects_tr1']);

	// var_dump($_SESSION['subselectObj']);

	$hasNext = "true";
	if ($_SESSION['step'] >= 3) {
		$hasNext = "false";
		$_SESSION['step'] = 0;
	}
	/*
	if (count($_SESSION['objects_tr1']) == 0 || !isset($_SESSION['objects_tr1'])) {
		$hasNext = "false";
	}*/

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
		}
	}
	
	$obj_index = $_SESSION['train1_order'][$_SESSION['step']-1];
	
	// update participant status
	$sql = "UPDATE Objects set `subset1_".$obj_index."_5`='".str_replace(".png", "", implode(":", $tr1_subselect5))."'"
		." WHERE `participant_id`=".$uuid.";";
	execSQL($sql);
?>

<!DOCTYPE html>
<html>
<head>
	<title>Subset selection - 1</title>
	<?php printMetaInfo(); ?>

	<script type="text/javascript">
		var cnt=0;

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
					$(this).find('img').css('border', "solid 0px");
					cnt--;
				}
				else {
					if (cnt <= 0) {
						$(this).addClass('image-checkbox-checked');
						$(this).find('input[type="checkbox"]').attr("checked", "checked");
						$(this).find('img').addClass("img-selected");
						$(this).find('img').css('border', "solid 4px #33cc33");
						$(this).find('img').css('padding', "0px");
						cnt++;
					}
				}

				e.preventDefault();
				
				$("#showCnt").text("Selected "+cnt+" out of 5");
				if (cnt == 1) {
					$("#nextButton").show();
				} else {
					$("#nextButton").hide();
				}
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
			/*outline: 4px solid #33cc33;*/
		}

		.imgselect {
			padding: 5px;
		}
	</style>
</head>
<body>
	<div class="mt-3 mb-3 mr-3 ml-3">
		<?php printProgressBar(5); ?>

		<h3>Select the best image out of the 5 you just chose of <?php echo $obj ?>!</h3>

		<form id="selection" action="" method="post">
			<p>A green border will appear around the images you select:</p>
				<?php
				$files = glob("images/p" . $uuid . "/t" . $_SESSION['trial'] ."/train1/" . $currObj . "/*.png");
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
			<div id='showCnt'>Selected 0 out of 5</div>		
		
			<div align='right'>
				<button type="button" id='nextButton' onclick="limitCheck()" class="btn btn-default">Next ></button>
			</div>
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
?>  