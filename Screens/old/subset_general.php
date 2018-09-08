<!-- CSS and jQuery adapted from http://www.prepbootstrap.com/bootstrap-template/image-checkbox -->
<!-- Takes images from train1 folder and saves selected filenames into $scr5_subselect20 -->
<?php
	$prev_select = 0;
	$img_size = 100;
	if ($subset_num == 20) {
		$prev_select = 1;
		$_SESSION['step'] = $_SESSION['step'] + 1;
	} else if ($subset_num == 5) {
		$prev_select = 20;
	} else if ($subset_num == 1) {
		$prev_select = 5;
		$img_size = 150;
	}
	
	$obj_index = $_SESSION['order'][$phase_for][$_SESSION['step']-1];
	$currObj = $_SESSION['object_names'][$obj_index-1];
	
	$obj = str_replace('_', ' ', $currObj);
	$uuid = $_SESSION['pid']; 
	// echo $currObj;
	$_SESSION['curr'] = $currObj;

	$subselect = range(1, 30);
	
	if ($_SESSION['step'] == 1 && $subset_num == 20) {
		// trigger the training for now
		require(dirname(__FILE__).'/../TOR/rest_client.php');
		// send the training images to the server
		$puuid = 'p' . $uuid;
		$trial = 't' . $_SESSION['trial'];
		// TODO: we need to trigger a training only once
		prepare_upload($puuid, $trial, $phase_for);
	} else {
		$subselect = array();

		// Code from https://www.formget.com/php-checkbox/
		if(isset($_POST['selections']) && is_array($_POST['selections']))
		{ //to run PHP script on submit
			if(!empty($_POST['selections']))
			{
				// Copy each file name into $scr5_subselect20
				foreach($_POST['selections'] as $selected)
				{
					$subselect[] = $selected;
				}
			}
		}
		// update participant status
		if ($subset_num == 20) {
			$obj_index = $_SESSION['order'][$phase_for][$_SESSION['step']-2];
		}
		$sql = "UPDATE Objects set `".$phase."_".$obj_index."_".$prev_select."`='".implode(":", $subselect)."'"
			." WHERE `participant_id`=".$uuid.";";
		execSQL($sql);
		
		if ($subset_num == 20) {
			$subselect = range(1, 30);
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Subset selection - <?=$subset_num?></title>
	<?php printMetaInfo(); ?>

	<script type="text/javascript">
		var cnt=0;
		
		<?php
			if ($subset_num == 20) {
				echo 'var limit = 20;';
			} else {
				echo 'var limit = '.$subset_num.';';
			}
		?>
        //var limit = <?=$subset_num?>;
		function uploadImg() {
        	document.getElementById("uploadbtn").click();
        }

        // Limits images selected
        function limitCheck() {
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
					if (cnt < limit) {
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
				if (cnt == limit) {
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
		<?php 
			printProgressBar($progress); 
		
			if ($subset_num == 20) {
				echo "<h4>Subset Selection 20</h4>";
				echo "<p>If you were to choose only 20 out of the 30 training images to make the training faster which ones would they be?</p>";
			} else if ($subset_num == 5) {
				echo "<h4>If you were to choose only 5?</h4>";
			} else if ($subset_num == 1) {
				echo "<h4>If you were to choose only 1?</h4>";
			}
		?>
		
		<p>You can select an image by clicking on it. A green border will appear upon selection. </p>


		<!-- Form redirects to subset selection - 5 -->
		<form id="selection" action="<?=$next?>" method="post">
		<?php
			// Displays the images
			$img_path = "images/p" . $uuid . "/t" . $_SESSION['trial'] ."/".$phase_for."/" . $currObj . "/";
			$files = glob($img_path. "*.png");
			
			for ($i=1; $i<=count($files); $i++)
			{
				if (in_array($i, $subselect)) {
					echo '<label class="image-checkbox">';
						echo '<img src="'.$img_path.$i.'.png" style="width:'.$img_size.'.px; height:'.floor($img_size/$_SESSION['ratio']).'px;" class="imgselect"/>'."&nbsp;&nbsp;";
						echo '<input type="checkbox" name="selections[]" value="' . $i . '" />';
					echo '</label>'; 
				}
			}
			if ($subset_num == 20) {
				echo "<div id='showCnt'>Selected 0 out of 30</div>";
			} else if ($subset_num == 5) {
				echo "<div id='showCnt'>Selected 0 out of 20</div>";
			} else if ($subset_num == 1) {
				echo "<div id='showCnt'>Selected 0 out of 5</div>";
			}
		?>	
		
		<div align='right'>
			<button type="button" id='nextButton' onclick="limitCheck()" class="btn btn-default" style="display:none;">Next ></button>
		</div>
		</form>

	<p id="test"></p>
	
       
<?php
    include 'footer.php';
?>


