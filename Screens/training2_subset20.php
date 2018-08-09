<!-- CSS and jQuery adapted from http://www.prepbootstrap.com/bootstrap-template/image-checkbox -->
<!-- Takes images from train2 folder and saves selected filenames into $scr9_subselect20 -->
<?php
	include 'header.php';
	if (!isset($_SESSION)) { 
		session_start(); 
	}

	// ini_set('display_errors',1);
	// error_reporting(E_ALL);
	// var_dump($_SESSION['subselectObj']);

	// $currObj = $_SESSION['subselectObj'][key($_SESSION['subselectObj'])];
	// echo $currObj;
	$currObj = key($_SESSION['objects_tr2']);
	$obj = str_replace('_', ' ', $currObj);
	$uuid = $_SESSION['pid']; 
	$_SESSION['curr'] = $currObj;
?>

<!DOCTYPE html>
<html>
<head>
	<title>Subset Selection - 20</title>
	<?php printMetaInfo(); ?>

	<script type="text/javascript">
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

		<h3>Select the 20 best images out of the 30 you took of <?php echo $obj ?>!</h3>

		<!-- Form redirects to subset selection - 5 -->
		<form id="selection" action="training2_subset5.php" method="post">
			<p>A green border will appear around the images you select:</p>
				<?php
				var_dump($_SESSION['pid']);
				var_dump($_SESSION['trial']);
				var_dump($_SESSION['curr']);
				// Displays the images
				$files = glob("images/p" . $uuid . "/t" . $_SESSION['trial'] ."/train2/" . $currObj . "/*.png");
				for ($i=0; $i<count($files); $i++)
				{
					$num = $files[$i];
					$filename = basename($num);
					// echo "filename: " . $filename;
					echo '<label class="image-checkbox">';
						echo '<img src="'.$num.'" style="width:150px; height:150px;" class="imgselect"/>'."&nbsp;&nbsp;";
						echo '<input type="checkbox" name="selections[]" value="' . $filename . '" />';
					echo '</label>'; 
				}

				?>
			<p><button type="button" onclick="limitCheck()" class="btn btn-default">Next</button></p>
		</form>
	</div>
</body>
</html>

<?php 
// trigger the training for now
require(dirname(__FILE__).'/../TOR/rest_client.php');
// send the training images to the server
// init_recognizer("12345");
$uuid = "12345";
$phase = "train2";
// TODO: we need to trigger a training only once
prepare_upload($uuid, $phase);

?>
