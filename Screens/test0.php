
<?php 
    session_start();
    include 'connectDB.php';
    include 'header.php';
    savePageLog($_SESSION['pid'], "test0");

    ini_set('display_errors', 1);
    error_reporting(E_ALL);
        
?>

<!-- Uploads images to "test0" folder in server -->
<!-- PHP code adapted from http://php.net/manual/en/features.file-upload.php and https://gist.github.com/projectxcappe/1220777/9ec6a7e62fb9d7c9a93bd834fb434d7ae25ed6if5 -->

<!DOCTYPE html>
<html>
<head>
  	<?php printMetaInfo(); ?>
    <title>Test 0</title>


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
        
        function goNext() {
        	
        }
        
        function get_random_object() {
            $.ajax({
              type: "POST",
              url: "get_random_object.php",
              data: { 
                 phase: 'test0'
              },
              success: function (data) {
                console.log('random object: '+data);
              },
              error: function () { console.log('fail'); }
            }).done(function(o) {
              console.log('done'); 
            });
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
              url: "test_upload.php",
              data: { 
                 imgBase64: img.src,
                 phase: 'test0',
                 object_name: $("objects").text()
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
        
        // Refreshes bottom portion of the page to upload images
        $(document).ready(function () {
	        get_random_object();
        });
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

        <div id="objects" class="objects" align='center'>
        </div>
        
        <div align='center' style='display:inline-block;'>
            <video autoplay="true" id="videoElement" width="45%"></video><br>
            <button type="button" class="btn btn-primary" onclick="captureImage()">Take a Picture</button>

            <div class="card border-success mb-3">
              <div class="card-header">Result</div>
              <div class="card-body text-success">
              	<table style="table-layout:fixed;width:100%">
              		<tr>
              			<td>
			                <div id="output" style='display:inline-block;'></div>
			            </td>
			            <td>
			                <div id='rec_result' class="card-text" align='center'></div>
			            </td>
                </table>
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