<?php 
	include 'header.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
$objects = $_SESSION['objects_ts0'];

$obj1 = key($objects);
next($objects);
$obj2 = key($objects);
next($objects);
$obj3 = key($objects);
reset($objects);

$count1 = $objects[$obj1];
$count2 = $objects[$obj2];
$count3 = $objects[$obj3];
$randObj = "";

echo randomize();

// echo "<p></p>";
// echo "count 1: " . $_SESSION['objects_ts0'][$obj1];
// echo "<p></p>";
// echo "count 2: " . $_SESSION['objects_ts0'][$obj2];
// echo "<p></p>";
// echo "count 3: " . $_SESSION['objects_ts0'][$obj3];
// echo "<p></p>";

function randomize() {
	global $objects, $randObj, $obj1, $obj2, $obj3;
	// $myObj = array('obj1', 'obj2', 'obj3');
	if ($_SESSION['objects_ts0'][$obj1] < 5 || $_SESSION['objects_ts0'][$obj2] < 5 || $_SESSION['objects_ts0'][$obj3] < 5) {		// don't execute if all counts are 5
		$randObj = array_rand($objects, 1);
		// echo "<p></p>";
		// echo $randObj;
		// Ensures each object is called 5 times
		while ($objects[$randObj] >= 5) {
			$randObj = array_rand($objects, 1);
		}

		$_SESSION['objects_ts0'][$randObj]++;
		
		return $randObj;
	}
}
// echo $randObj;
?>

<!-- Uploads images to "initialtest" folder in server -->
<!-- PHP code adapted from http://php.net/manual/en/features.file-upload.php and https://gist.github.com/projectxcappe/1220777/9ec6a7e62fb9d7c9a93bd834fb434d7ae25ed6if5 -->

<!DOCTYPE html>
<html>
<head>
    <title>Test 1</title>
    <?php printMetaInfo(); ?>

    <script>
        // Refreshes bottom portion of the page to upload and show images
        $(document).ready(function () {
            $('form').on('submit', function (e) {
              e.preventDefault();

            $.ajax({
                type: 'post',
                url: 'screen3_upload.php',
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function () {
                    $("#images").load("screen3_showimages.php");
                    $("#test").load("screen3_upload.php");              
              }
          });
          });
        });
    </script>

    <script type="text/javascript">
        var obj1 = {"id":"obj1","count":"0"};
        var obj2 = {"id":"obj2","count":"0"};
        var obj3 = {"id":"obj3","count":"0"};
        var button = {"id":"objButton","count":"0"};
        var arr = [obj1, obj2, obj3];

        // For "Get Object" button; selects a random object for user to test
        function randomize() {

            button.count++;
            // document.getElementById("buttoncount").innerHTML = button.count;
            // Hides the previous object
            for (var i = 0; i < arr.length; i++) {
                document.getElementById(arr[i].id).style.display = "none";
            }
            var randObj = arr[Math.floor(Math.random() * arr.length)];
            // Ensures each object can only be called 5 times
            while (randObj.count + 1 > 5) {
                randObj = arr[Math.floor(Math.random() * arr.length)];
            }
            randObj.count++;
            
            document.getElementById(randObj.id).style.display = "block";

            // Hides the button after all objects are called 5 times
            if (button.count >= 15) {
                document.getElementById(button.id).style.display = "none";
            }

        }

        // For "Take a Picture" button
        function takePic() {
            document.getElementById("fileToUpload").click();
        }

        // For "Upload Image" button
        function uploadImg() {
        	var obj = document.getElementById("objects").innerHTML;
        	// $.post('screen3.upload.php', {obj: obj});

        	$.ajax({
			  type: "POST",
			  url: "image_upload.php",
			  data: {obj: obj}
			}).done(function() {
			  alert( "Data Saved!" );
			});

        	document.getElementById("test").innerHTML = obj;
        	// Get whichever object is showing on the screen and send it to upload and show images files? then in those files do if statement
        	document.getElementById("uploadbtn").click();
        }
    </script>

</head>
<body>
	<div class="mt-3 mb-3 mr-3 ml-3">
		<?php printProgressBar(3); ?>

		<h3>Let's test our system!</h3>
		<p>Click <mark>Get Object</mark>  to see which object to photograph, then click <mark>Upload</mark>  to send in your picture. <mark>Get Object</mark>  will disappear once you've taken 5 images total for each object.</p>
		<p class="text-info">(Here's a hint: don't be scared if the object doesn't change! It's randomized, so if you've clicked the button and it doesn't change, take another picture and send it in.)</p>
	
		<p><button type="button" class="btn btn-primary" id="objButton" onclick="randomize()">Get Object</button></p>

		<div id="objects" class="objects">
			<p id="obj1" hidden="true">Object 1</p>
			<p id="obj2" hidden="true">Object 2</p>
			<p id="obj3" hidden="true">Object 3</p>
		</div>

		<div id="test"></div>

		<form action="screen3_upload.php" method="post" enctype="multipart/form-data">
			<span class="test-text">Test</span> the object recognizer with your images:
			<input type="file" style="display: none;" accept="image/*" capture="camera" name="fileToUpload" id="fileToUpload" required="true">
			<p><button type="button" class="btn btn-primary" onclick="takePic()">Take a Picture</button></p>
			<input type="submit" id="uploadbtn" value="Upload Image" name="submit" required="true" style="display: none;">
			<p><button type="button" class="btn btn-primary" onclick="uploadImg()">Upload Image</button></p>
		</form>

		<div id="images"></div>

		<p>[Feedback from object recognizer will go here]</p>

		<button type="button" class="btn btn-default" onclick="window.location.href='before_training1.php'">Next</button>
	</div>
</body>
</html>
