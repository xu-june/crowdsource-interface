<!-- CSS and jQuery adapted from http://www.prepbootstrap.com/bootstrap-template/image-checkbox -->
<!-- Takes images from train2 folder and saves selected filenames into $scr9_subselect20 -->
<?php
    session_start();
	include 'header.php';
    include 'connectDB.php';
    savePageLog($_SESSION['pid'], "train2_subset20");
    
	// ini_set('display_errors',1);
	// error_reporting(E_ALL);
	// var_dump($_SESSION['train2_order']);

	// $currObj = $_SESSION['subselectObj'][key($_SESSION['subselectObj'])];
	
	$obj_index = $_SESSION['train2_order'][$_SESSION['step']];
	$currObj = $_SESSION['object_names'][$obj_index-1];
	
	$obj = str_replace('_', ' ', $currObj);
	$uuid = $_SESSION['pid']; 
	// echo $currObj;
	$_SESSION['curr'] = $currObj;

	if ($_SESSION['step'] == 0) {
		// trigger the training for now
		require(dirname(__FILE__).'/../TOR/rest_client.php');
		// send the training images to the server
		$puuid = 'p' . $uuid;
		$trial = 't' . $_SESSION['trial'];
		$phase = "train2";
		// TODO: we need to trigger a training only once
		prepare_upload($puuid, $trial, $phase);
	} else {
		$tr2_subselect1 = array();

		// Code from https://www.formget.com/php-checkbox/
		if(isset($_POST['selections']) && is_array($_POST['selections']))
		{ //to run PHP script on submit
			if(!empty($_POST['selections']))
			{
				// Copy each file name into $scr5_subselect20
				foreach($_POST['selections'] as $selected)
				{
					$tr2_subselect1[] = $selected;
				}
			}
		}
		$obj_index = $_SESSION['train2_order'][$_SESSION['step']-1];
		// update participant status
		$sql = "UPDATE Objects set `subset2_".$obj_index."_1`='".str_replace(".png", "", implode(":", $tr2_subselect1))."'"
			." WHERE `participant_id`=".$uuid.";";
		execSQL($sql);
	}
	$_SESSION['step']++;
		//$_SESSION['training_img_num'] = 30; // temporary value for debugging
?>

<!DOCTYPE html>
<html>
<head>
	<title>Subset Selection - 20</title>
	<?php printMetaInfo(); ?>

	<script type="text/javascript">
		var cnt=0;
		function uploadImg() {
        	document.getElementById("uploadbtn").click();
        }

        // Limits images selected
        function limitCheck() {
        	var limit = 20;
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
					if (cnt <= 19) {
						$(this).addClass('image-checkbox-checked');
						$(this).find('input[type="checkbox"]').attr("checked", "checked");
						$(this).find('img').addClass("img-selected");
						$(this).find('img').css('border', "solid 4px #33cc33");
						$(this).find('img').css('padding', "0px");
						cnt++;
					}
				}

				e.preventDefault();
				
				$("#showCnt").text("Selected "+cnt+" out of 30");
				if (cnt == 20) {
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

	<h3>Subset Selection 20</h3>
		
		<p>If you were to choose only 20 out of the 30 training images to make the training faster which ones would they be?</p>

		<p>You can select an image by clicking on it. A green border will appear upon selection. </p>


		<!-- Form redirects to subset selection - 5 -->
		<form id="selection" action="training2_subset5.php" method="post">
				<?php
					//var_dump($_SESSION['pid']);
					//var_dump($_SESSION['trial']);
					// var_dump($_SESSION['curr']);
					// Displays the images
					$files = glob("images/p" . $uuid . "/t" . $_SESSION['trial'] ."/train2/" . $currObj . "/*.png");
					for ($i=0; $i<count($files); $i++)
					{
						$num = $files[$i];
						$filename = basename($num);
						// echo "filename: " . $filename;
						echo '<label class="image-checkbox">';
							echo '<img src="'.$num.'" style="width:100px; height:100px;" class="imgselect"/>'."&nbsp;&nbsp;";
							echo '<input type="checkbox" name="selections[]" value="' . $filename . '" />';
						echo '</label>'; 
					}
				?>
		
		<div id='showCnt'>Selected 0 out of 30</div>		
		
		<div align='right'>
			<button type="button" id='nextButton' onclick="limitCheck()" class="btn btn-default">Next ></button>
			<!-- <button type="button" id='nextButton' onclick="window.location.href='training2_subset5.php'" class="btn btn-default">Next ></button> -->
		</div>
		</form>

	<p id="test"></p>
</body>
</html>

<?php
    include 'footer.php';
?>



