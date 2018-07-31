<?php
	include 'header.php';
?>

<!DOCTYPE html>
<html>
<head>
	<title>Training 1</title>
	<?php printMetaInfo(); ?>

	<script>
        // Refreshes bottom portion of the page to upload and show images
        $(document).ready(function () {
            $('form').on('submit', function (e) {
              e.preventDefault();

            $.ajax({
                type: 'post',
                url: 'screen5_upload.php',
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function () {
                    $("#images").load("screen5_showimages.php");              
              }
          });
          });
        });
    </script>

    <script type="text/javascript">
		var obj1 = {"id":"obj1","count":"0"};
		var obj2 = {"id":"obj2","count":"0"};
		var obj3 = {"id":"obj3","count":"0"};
		var button = {"id":"uploadButton","count":"0"};
		var arr = [obj1, obj2, obj3];

		// For "Get Object" button; selects a random object for user to train
		function randomize() {

			// Hides the previous object
			for (var i = 0; i < arr.length; i++) {
				document.getElementById(arr[i].id).style.display = "none";
			}
			var randObj = arr[Math.floor(Math.random() * arr.length)];
			// Ensures that each object is only called once
			while (randObj.count > 0) {
				randObj = arr[Math.floor(Math.random() * arr.length)];
			}
			randObj.count++;

			document.getElementById(randObj.id).style.display = "block";

			// Hides the button after new object is shown
			document.getElementById("objButton").style.display = "none";

		}

		// For "Upload" button; counts number of images trained for each object and uploads images to server (eventually)
		function countPics() {

			button.count++;

			if (button.count < 90) {
				// Allows user to train new object after 30 images
				if (button.count % 30 === 0) {
					document.getElementById("objButton").style.display = "block";
				}
			}
			// Ensures user only trains 90 images total
			else {
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
		<?php printProgressBar(4); ?>

		<h3>Add your own objects!</h3>
		<p>Take 30 pictures of the requested object. Click on <mark>Get Object</mark>, then click <mark>Upload</mark> to upload your image of the object. The counter at the bottom will help you keep track of how many images you've uploaded.</p>
		<p>Once you complete 30 images for each object, click <mark>Get Object</mark>again to know what to train next! You'll know you're finished when <mark>Upload</mark> disappears.</p>

		<p>
			<button type="button" class="btn btn-primary" id="objButton" onclick="randomize()">Get Object</button>
		</p>

		<div class="objects">
			<p id="obj1" hidden="true">Object 1</p>
			<p id="obj2" hidden="true">Object 2</p>
			<p id="obj3" hidden="true">Object 3</p>
		</div>

		<p></p>
		<form action="training1_upload.php" method="post" enctype="multipart/form-data">
			<span class="train-text">Train</span> the object recognizer with your images:
			<input type="file" style="display: none;" accept="image/*" capture="camera" name="fileToUpload" id="fileToUpload">
			<p><button type="button" class="btn btn-primary" onclick="takePic()">Take a Picture</button></p>
			<input type="submit" id="uploadbtn" value="Upload Image" name="submit" onclick="countPics()" style="display: none;">
			<p><button type="button" class="btn btn-primary" onclick="uploadImg()">Upload Image</button></p>
		</form>

		<div id="images"></div>

		<p id="wholeCounter"><span id="counter">0</span>/30 images taken!</p>

		<button type="button" class="btn btn-default" onclick="window.location.href='training1_subset20.php'">Next</button>
	</div>
</body>
</html>