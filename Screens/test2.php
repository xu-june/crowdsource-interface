<!-- Uploads images to "test1" folder in server -->
<!-- PHP code adapted from http://php.net/manual/en/features.file-upload.php and https://gist.github.com/projectxcappe/1220777/9ec6a7e62fb9d7c9a93bd834fb434d7ae25ed6f5 -->
<?php
	include 'header.php';
?>

<!DOCTYPE html>
<html>
<head>
	<title>Test 2</title>
	<?php printMetaInfo(); ?>

	<script>
        // Refreshes bottom portion of the page to upload and show images
        $(document).ready(function () {
            $('form').on('submit', function (e) {
              e.preventDefault();

            $.ajax({
                type: 'post',
                url: 'screen6_upload.php',
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function () {
                    $("#images").load("screen6_showimages.php");              
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
        	document.getElementById("uploadbtn").click();
        }
    </script>
</head>
<body>
    <div class="mt-3 mb-3 mr-3 ml-3">
		<?php printProgressBar(6); ?>
    
	<h3>Let's see how well you did!</h3>	
	<p>Take a picture of the requested object to see how well you did. Again, click <i class="buttonname">Get Object</i> to know which one to take a picture of, then <i class="buttonname">Upload</i> to upload it. You'll be done when <i class="buttonname">Get Object</i> disappears.</p>

	<p><button type="button" class="btn btn-primary" id="objButton" onclick="randomize()">Get Object</button></p>

	<div class="objects">
    	<p id="obj1" hidden="true">Object 1</p>
	    <p id="obj2" hidden="true">Object 2</p>
	    <p id="obj3" hidden="true">Object 3</p>
    </div>

	<p></p>

	<form action="screen6_upload.php" method="post" enctype="multipart/form-data">
		<span class="test-text">Test</span> the object recognizer with your images:
		<input type="file" style="display: none;" accept="image/*" capture="camera" name="fileToUpload" id="fileToUpload" required="true">
		<p><button type="button" class="btn btn-primary" onclick="takePic()">Take a Picture</button></p>
		<input type="submit" id="uploadbtn" value="Upload Image" name="submit" style="display: none;">
        <p><button type="button" class="btn btn-primary" onclick="uploadImg()">Upload Image</button></p>
	</form>

	<div id="images"></div>

	<p>[Feedback from object recognizer will go here]</p>

	<button type="button" class="btn btn-default" onclick="window.location.href='feedbackscreen1.php'">Next</button>

</body>
</html>