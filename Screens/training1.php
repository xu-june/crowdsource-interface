<?php
    session_start();
    include 'connectDB.php';
    include 'header.php';
    savePageLog($_SESSION['pid'], "train1");
    
    $imgPath = 'images/p' . $_SESSION['pid'] . '/t' .$_SESSION['trial'] .'/train1/';
?>

<!-- Uploads images to "train1" folder in server -->
<!-- PHP code adapted from http://php.net/manual/en/features.file-upload.php and https://gist.github.com/projectxcappe/1220777/9ec6a7e62fb9d7c9a93bd834fb434d7ae25ed6if5 -->

<!DOCTYPE html>
<html>
<head>
    <title>Training 1</title>
    <?php printMetaInfo(); ?>

    <script type="text/javascript">
        var upload_cnt = 0;
        var subselectObj = [];
        
        function get_random_object() {
            $.ajax({
              type: "POST",
              url: "get_random_object.php",
              data: { 
                 phase: 'train1'
              },
              success: function (data) {
                console.log('random object: '+data);

				$objects = $("#objects");
				$objects.empty();
				
                if (data == 'this step is done') {
                	window.location.href='training1_subset20.php';
                } else {
                	var words = data.split(' ');
                	var objectname = words[0];
                    var objectname_space = objectname.replace(/_/g,' ');
                	var count = words[1];
                	upload_cnt = count-1;
                	console.log('uc: ' + upload_cnt);
                	
	                $objects = $("#objects");
	                $objects.empty();
                	$objects.append(objectname);
                	
            		load_images();
            		$("#nextButton").hide();
                	
                	// show modal
                	$modalLabel = $("#guideBody");
                	$modalLabel.empty();
                	$modalLabel.append("<h1 class='bg-warning' align='center'>" +objectname_space + "</h1>"
                					+"Bring your "+objectname_space+" to take pictures.");
                    subselectObj.push(objectname);
            		$("#triggerModal").click();
                }
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
            
            $("#rec_result").empty();
            $("#nextContainer").empty();
            
            $.ajax({
              type: "POST",
              url: "upload.php",
              data: { 
                 imgBase64: img.src,
                 phase: 'train1',
                 objectname: $("#objects").text().split(" ")[0]
              },
              success: function (data) {
                console.log('success'+data);
                upload_cnt++;
                addImage(upload_cnt);
                fixImage(upload_cnt);
                
                if (upload_cnt >= 5) {
                	$("#nextButton").show();
                    document.getElementById("nextButton").scrollIntoView();
                }
              },
              error: function () { console.log('fail'); }
            }).done(function(o) {
              console.log('done'); 
            });
        }
        
        function addImage(index) {
			var path = '<?=$imgPath?>'+$("#objects").text().split(" ")[0]+'/'+index+'.png';
			var dummyPath = 'images/dummy.jpg'
			$images = $("#images");
			
			$images.prepend("<div class='ml-1 mr-1' style='display:inline-block;'>"
                				+"<div id='img"+index+"' style=\"width:100px;height:100px;background-image:url(\'"+path+"\');background-size:cover;\"></div>"
                				+index+"</div>");
        }
        
        function fixImage(index) {
        	var path = '<?=$imgPath?>'+$("#objects").text().split(" ")[0]+'/'+index+'.png';
			document.getElementById("img"+index).style.backgroundImage = "url('"+path+"')";
        }
        
        function load_images() {
	        $images = $("#images");
	        $images.empty();
			for (i = 0; i < upload_cnt; i++) { 
				addImage(i+1);
			}
        }
        
        // Refreshes bottom portion of the page to upload images
        $(document).ready(function () {
	        get_random_object();
            $("#triggerModal").click();
        });
    </script>
</head>
<body>
    <div class="mt-3 mb-3 mr-3 ml-3">
        <?php printProgressBar(4); ?>

        <h3>Add your own objects!</h3>
        <p>Take 30 pictures of the following object by tapping on the camera stream, and scrolling down to see how many images you've taken.</p>

        <h4><div id="objects" class="bg-warning" align='center'>
        </div></h4>
        
        <div id='buttonContainer' align='center' style='display:inline-block;'>
            <video autoplay="true" onclick="captureImage()" control="true" id="videoElement" width="100%" playsinline></video><br>
            <!-- <button type="button" class="btn btn-primary" onclick="captureImage()">Take</button> -->
            <button type='button' id='nextButton' class='btn btn-default' onclick='get_random_object();' style="display:none;">Next</button>
        </div>
        
        <br>

        <div id="images"></div>
        
        <div id='nextContainer' align='center'>
        </div>
        
        <!-- Button trigger modal -->
		<button type="button" id='triggerModal' class="btn btn-primary" data-toggle="modal" data-target="#guideModal" style="display:none;">
		  Launch modal
		</button>
        
        <!-- Modal -->
		<div class="modal fade" id="guideModal" tabindex="-1" role="dialog" aria-labelledby="guideModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
			  <div class="modal-header" align='center'>
				<h5 class="modal-title" id="guideModalLabel">Next item</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
				</button>
			  </div>
			  <div class="modal-body" id="guideBody">
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			  </div>
			</div>
		  </div>
		</div>
        
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

    </div>
</body>
</html>
        
<?php
    include 'footer.php';
?>
