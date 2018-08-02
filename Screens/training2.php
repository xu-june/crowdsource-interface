<?php
	include 'header.php';

    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    session_start();
    // Gets array of objects and counts
    $objects = $_SESSION['objects_tr2'];

    $obj1 = $_SESSION['obj1'];
    $obj2 = $_SESSION['obj2'];
    $obj3 = $_SESSION['obj3'];

    $randObj = "";

    // echo "<p></p>";
    // echo "count 1: " . $_SESSION['objects_ts0'][$obj1];
    // echo "<p></p>";
    // echo "count 2: " . $_SESSION['objects_ts0'][$obj2];
    // echo "<p></p>";
    // echo "count 3: " . $_SESSION['objects_ts0'][$obj3];
    // echo "<p></p>";

    // Randomizes object labels
    function randomize() {
        global $objects, $randObj, $obj1, $obj2, $obj3;
        // Ensures that this executes until all objects have been shown 5 times
        if ($_SESSION['objects_tr2'][$obj1] < 1 || $_SESSION['objects_tr2'][$obj2] < 1 || $_SESSION['objects_tr2'][$obj3] < 1) {
            $randObj = array_rand($objects, 1);
            // Ensures each object is called 5 times
            while ($objects[$randObj] >= 1) {
                $randObj = array_rand($objects, 1);
            }
            // Increases the count for the object
            $_SESSION['objects_tr2'][$randObj]++;
            // Sends object to upload file
            $_SESSION['currObj'] = $randObj;
            return $randObj;
        }
    }
?>

<!-- Uploads images to "train2" folder in server -->
<!-- PHP code adapted from http://php.net/manual/en/features.file-upload.php and https://gist.github.com/projectxcappe/1220777/9ec6a7e62fb9d7c9a93bd834fb434d7ae25ed6if5 -->
		
<!DOCTYPE html>
<html>
<head>
	<title>Training 2</title>
	<?php printMetaInfo(); ?>

	<script>
        // Refreshes bottom portion of the page to upload and show images
        $(document).ready(function () {
            $('form').on('submit', function (e) {
              e.preventDefault();

            $.ajax({
                type: 'post',
                url: 'training2_upload.php',
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function () {
                    $("#images").load("training2_showimages.php");              
              }
          });
          });
        });
    </script>

    <script type="text/javascript">
		var button = {"id":"uploadButton","count":"0"};

		// For "Get Object" button
        function reload() {
            window.location.reload();
        }

		// For "Upload" button; counts number of images trained for each object and uploads images to server (eventually)
		function countPics() {

			button.count++;

			if (button.count >= 90) {
				document.getElementById(button.id).style.display = "none";
				document.getElementById("wholeCounter").innerHTML = "You're done! Please move on to the next page."
			}

			// Displays the number of images uploaded
			document.getElementById("counter").innerHTML = button.count % 30;

		}

		// For "Take a Picture" button
		function takePic() {
			document.getElementById("fileToUpload").click();
		}

		// For "Upload Image" button
        function uploadImg() {
        	document.getElementById("uploadbtn").click();
        }
	</script>
</head>
<body>
	<div class="mt-3 mb-3 mr-3 ml-3">
		<?php printProgressBar(8); ?>

		<h3>Round 2: Put your ideas in motion!</h3>
		<p>Take pictures of the requested object, like you did before. Again, click on <mark>Get Object</mark>, then click <mark>Upload</mark> 
		after taking each photo. The counter at the bottom will help you keep track of how many images you've already taken. 
		Click on <mark>Get Object</mark> after each 30 images to know what to capture next! You'll know you're finished when <mark>Upload</mark> disappears.</p>

		<p>
			<button type="button" class="btn btn-primary" id="objButton" onclick="reload()">Get Object</button>
		</p>

		<div id="objects" class="objects">
			<?php echo randomize(); ?>
		</div>

		<p></p>
		<!-- is screen9_upload different from screen5_upload? I made it use the same php file for uploading file -->
		<form action="training2_upload.php" method="post" enctype="multipart/form-data">
			<span class="train-text">Train</span> the object recognizer with your images:
			<input type="file" style="display: none;" accept="image/*" capture="camera" name="fileToUpload" id="fileToUpload">
			<p><button type="button" class="btn btn-primary" onclick="takePic()">Take a Picture</button></p>
			<input type="submit" id="uploadbtn" value="Upload Image" name="submit" onclick="countPics()" style="display: none;">
			<p><button type="button" class="btn btn-primary" onclick="uploadImg()">Upload Image</button></p>
		</form>

		<div id="images"></div>

		<p id="wholeCounter"><span id="counter">0</span>/30 images taken!</p>

		<button type="button" class="btn btn-default" onclick="window.location.href='training2_subset20.php'">Next</button>
	</div>
</body>
</html>