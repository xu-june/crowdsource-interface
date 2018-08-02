<?php 
	session_start();
	include 'connectDB.php';
	include 'header.php';
	savePageLog($_SESSION['pid'], "test0");

    ini_set('display_errors', 1);
    error_reporting(E_ALL);
        
    // Gets array of objects and counts
    $objects = $_SESSION['objects_ts0'];

    $obj1 = key($objects);
    $_SESSION['obj1'] = $obj1;
    next($objects);
    $obj2 = key($objects);
    $_SESSION['obj2'] = $obj2;
    next($objects);
    $obj3 = key($objects);
    $_SESSION['obj3'] = $obj3;
    reset($objects); 

    $randObj = "";

    // echo "<p></p>";
    // echo "count 1: " . $_SESSION['objects_ts0'][$obj1];
    // echo "<p></p>";
    // echo "count 2: " . $_SESSION['objects_ts0'][$obj2];
    // echo "<p></p>";
    // echo "count 3: " . $_SESSION['objects_ts0'][$obj3];
    // echo "<p></p>";

    function randomize() {
        global $objects, $randObj, $obj1, $obj2, $obj3;
        // Ensures that this executes until all objects have been shown 5 times
        if ($_SESSION['objects_ts0'][$obj1] < 5 || $_SESSION['objects_ts0'][$obj2] < 5 || $_SESSION['objects_ts0'][$obj3] < 5) {
            $randObj = array_rand($objects, 1);
            // Ensures each object is called 5 times
            while ($objects[$randObj] >= 5) {
                $randObj = array_rand($objects, 1);
            }
            // Increases the count for the object
            $_SESSION['objects_ts0'][$randObj]++;
            // Sends object to upload file
            $_SESSION['currObj'] = $randObj;
            return $randObj;
        }
    }
?>

<!-- Uploads images to "test0" folder in server -->
<!-- PHP code adapted from http://php.net/manual/en/features.file-upload.php and https://gist.github.com/projectxcappe/1220777/9ec6a7e62fb9d7c9a93bd834fb434d7ae25ed6if5 -->

<!DOCTYPE html>
<html>
<head>
    <title>Test 0</title>
    <?php printMetaInfo(); ?>

    <script>
        // Refreshes bottom portion of the page to upload images
        $(document).ready(function () {
            $('form').on('submit', function (e) {
              e.preventDefault();

            $.ajax({
                type: 'post',
                url: 'test0_upload.php',
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function () {
                    $("#done").load("test0_upload.php");            
              }
          });
          });
        });
    </script>

    <script type="text/javascript">
        // For "Get Object" button
        function reload() {
            window.location.reload();
        }

        // For "Take a Picture" button
        function takePic() {
            document.getElementById("fileToUpload").click();
        }

        // For "Upload Image" button
        function uploadImg() {
        	document.getElementById("uploadbtn").click();
        }
        
        function captureImage() {
        	var video = document.getElementById("videoElement")
			var canvas = document.createElement("canvas");
			var scale = 1.0
			
			canvas.width = video.videoWidth * scale;
			canvas.height = video.videoHeight * scale;
			canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
 
			var img = document.createElement("img");
			img.height = video.videoHeight/4;
			img.width = video.videoWidth/4;
			img.src = canvas.toDataURL();

			$output = $("#output");
			$output.empty();
			$output.prepend(img);
			
			$.ajax({
			  type: "POST",
			  url: "test0_upload.php",
			  data: { 
				 imgBase64: img.src,
				 filename: '<?php echo $_SESSION['pid']."_tmpObj_test0"?>'
			  },
			  success: function (data) {
			  	console.log('success'+data);
			  	$rec_result = $("#rec_result");
			  	$rec_result.append(data);
			  },
			  error: function () { console.log('fail'); }
			}).done(function(o) {
			  console.log('done'); 
			});
        }
    </script>

</head>
<body>
	<div class="mt-3 mb-3 mr-3 ml-3">
		<?php printProgressBar(3); ?>

		<h3>Let's test our system!</h3>
		<p>Click <mark>Get Object</mark>  to see which object to photograph, then click <mark>Take a picture</mark>  to send in your picture. 
		<mark>Get Object</mark>  will disappear once you've taken 5 images total for each object.</p>
		
		<p class="text-info">(Here's a hint: don't be scared if the object doesn't change! It's randomized, 
		so if you've clicked the button and it doesn't change, take another picture and send it in.)</p>
		
		<div align="center">
			<p><button type="button" class="btn btn-primary" id="objButton" onclick="reload()">Get Object</button></p>
		</div>

		<div id="objects" class="objects">
			<?php echo randomize(); ?>
		</div>
		
		<div align='center' style='display:inline-block;'>
			<video autoplay="true" id="videoElement" width="45%"></video><br>
			<button type="button" class="btn btn-primary" onclick="captureImage()">Take a Picture</button>

			<div class="card border-success mb-3">
			  <div class="card-header">Result</div>
			  <div class="card-body text-success">
				<div id="output" style='display:inline-block;'></div>
				<div id='rec_result' class="card-text" style='display:inline-block;'></div>
			  </div>
			</div>
		</div>
		
		<br>
		
		<button type="button" class="btn btn-default" onclick="window.location.href='before_training1.php'">Next</button>
		
		<script>
			 var video = document.querySelector("#videoElement");
			const constraints = {
				advanced: [{
					facingMode: "environment"
				}]
			};
			navigator.mediaDevices.getUserMedia({
				video: constraints
			}).then((stream) => {
				video.srcObject = stream;
			}).catch(function(err0r) {
				console.log("Something went wrong!");
			  });
		</script>
		
<?php
	include 'footer.php';
?>