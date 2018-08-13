<!-- CSS and jQuery adapted from http://www.prepbootstrap.com/bootstrap-template/image-checkbox -->
<!-- Takes images from train2 folder and saves selected filenames into $scr9_subselect5 -->
<?php
    session_start();
	include 'header.php';
    include 'connectDB.php';
    savePageLog($_SESSION['pid'], "train2_subset5");

 //    ini_set('display_errors',1);
	// error_reporting(E_ALL);

	$currObj = $_SESSION['curr'];
	$obj = str_replace('_', ' ', $currObj);
	$uuid = $_SESSION['pid'];

	$tr2_subselect20 = array();
	// Code from https://www.formget.com/php-checkbox/
	if(isset($_POST['selections']) && is_array($_POST['selections']))
	{ //to run PHP script on submit
		if(!empty($_POST['selections']))
		{
			// Copy each file name into $scr5_subselect20
			foreach($_POST['selections'] as $selected)
			{
				$tr2_subselect20[] = $selected;
				// echo "array is being copied";
			}
		}
	}
	
	//echo implode(",", $tr1_subselect20);

	$obj_index = $_SESSION['train2_order'][$_SESSION['step']-1];
	// update participant status
	$sql = "UPDATE Objects set `subset2_".$obj_index."_20`='".str_replace(".png", "", implode(":", $tr2_subselect20))."'"
		." WHERE `participant_id`=".$uuid.";";
	execSQL($sql);
?>


<!DOCTYPE html>
<html>
<head>
	<title>Subset Selection - 5</title>
	<?php printMetaInfo(); ?>

	<script type="text/javascript">
		var cnt=0;
		function uploadImg() {
        	document.getElementById("uploadbtn").click();
        }

        // Limits images selected
        function limitCheck() {
        	var limit = 5;
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
        		document.getElementById("selection").submit();
        	}
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
					if (cnt <= 4) {
						$(this).addClass('image-checkbox-checked');
						$(this).find('input[type="checkbox"]').attr("checked", "checked");
						$(this).find('img').css('border', "solid 4px #33cc33");
						$(this).find('img').css('padding', "0px");
						cnt++;
					}
				}

				e.preventDefault();
				
				$("#showCnt").text("Selected "+cnt+" out of 20");
				if (cnt == 5) {
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
		<?php printProgressBar(15); ?>

		<h3>Select the 5 best images out of the 20 you just chose of <?php echo $obj ?>!</h3>

	<!-- Form redirects to subset selection - 1 -->
	<form id="selection" action="training2_subset1.php" method="post">
		<p>A green border will appear around the images you select:</p>
			<?php
			// ini_set('display_errors',1);
			// error_reporting(E_ALL);
			// var_dump($_SESSION['curr']);
			// Displays the images
			$files = glob("images/p" . $uuid . "/t" . $_SESSION['trial'] ."/train2/" . $currObj . "/*.png");
			for ($i=0; $i<count($files); $i++)
			{
				$num = $files[$i];
				$filename = basename($num);
				// if $filename is contained in $scr5_subselect20, then display the picture
				if (in_array($filename, $tr2_subselect20)) {
					echo '<label class="image-checkbox">';
						echo '<img src="'.$num.'" style="width:100px; height:100px;" class="imgselect"/>'."&nbsp;&nbsp;";
						echo '<input type="checkbox" name="selections[]" value="' . $filename . '" />';
					echo '</label>'; 
				}
			}
			?>
		<div id='showCnt'>Selected 0 out of 20</div>		
		
		<div align='right'>
			<button type="button" id='nextButton' onclick="limitCheck()" class="btn btn-default">Next ></button>
			<!-- <button type="button" id='nextButton' onclick="window.location.href='training2_subset1.php'" class="btn btn-default">Next ></button> -->
		</div>
	</form>

	</div>
</body>
</html>
