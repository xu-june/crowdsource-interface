<?php
    // count files already uploaded
    $upload_cnt = 0;
    $obj_cnt = array(0, 0, 0);
    $imgPath = 'images/p' . $_SESSION['pid'] . '/t' .$_SESSION['trial'] .'/'.$phase.'/';
    for ($i=0; $i<count($_SESSION["object_names"]); $i++) {
        $obj = $_SESSION["object_names"][$i];
        $files = glob($imgPath . $obj . "/*.png");
        $upload_cnt += count($files);
        $obj_cnt[$i] = count($files);
    }
?>

<!DOCTYPE html>
<html>
<head>
    <?php printMetaInfo(); ?>
    <title>Test with your images</title>

    <script type="text/javascript">
        var upload_cnt = <?=$upload_cnt?>;
        var clickable = false;
        var obj_index = [<?=implode(',', $_SESSION['order'][$phase])?>];
        var obj_names = ["<?=implode('","', $_SESSION['object_names'])?>"];
        var obj_counts = [<?=implode(',', $obj_cnt)?>];
        var bgColors = ['#76D7C4', '#FAD7A0', '#D7BDE2'];
        var test_img_num = <?=$_SESSION['test_img_num']?>;
        
        // Refreshes bottom portion of the page to upload images
        $(document).ready(function () {
        	videoElement = document.getElementById("videoElement");
			get_random_object_test(test_img_num);		
	            
			if (upload_cnt < test_img_num*3) {
				start_overlay_on();
			}
            
            videoElement.onloadedmetadata = function() {
            	clickable = true;
		        createOffscreenCanvas(videoElement);
				document.getElementById('interface').scrollTop = 10;
	            document.getElementById("interface").scrollIntoView(true);		
			};
        });
        
    </script>

    <style type="text/css">
        #videoElement {
            border: 8px solid black;
        }
    </style>

</head>
<body>
    <div class="mt-3 mb-3 mr-3 ml-3">

        
        <div align='center' style='position:relative;' id='interface'>
			<h4><div id="objects" align='center'>
			</div></h4>

            <video autoplay="true" onclick="captureImage_test(test_img_num, '<?=$phase?>')" control="true" id="videoElement" width="100%" playsinline></video><br>
            <div id='preview' class="mb-3 ml-3" style="width:20vw;height:20vw;position:absolute;bottom:10px;">
                <div align='right'>
                    <div id='count' class='numCircle'>15</div>
                </div>
            
                <canvas id="canvas" style="background-color: black;"></canvas>
            </div>
        
        </div>
        <div id="output" style='background-color:#00FFF0;' align='center'></div>
        <canvas id="hiddenCanvas" style="position: absolute; z-index: -1"></canvas>
            
        <div align='right'>
            <button type="button" id='nextButton' class="btn btn-default" onclick="window.location.href='before_training1.php'" style="display:none;">Next ></button>
        </div>
        
        <div class="overlay" id="start_overlay" onclick="start_overlay_off()">
        	<?php printProgressBar($progress); ?>
        	<div class="overlay_contents">
				<h2>Testing images</h2>
				<p>Take a photo of an object (name at the top) by tapping in the camera screen.</p>
				<p>The recognition result will be shown at the bottom. Repeat until you take 15 photos of objects (5 for each).</p>
				<br>
				<br>
				<div align='right'> Start >>> </div>
        	</div>
        </div>
        
        <div class="overlay" id="end_overlay" onclick="window.location.href='<?=$next?>'">
        	<div class="overlay_contents">
				<h2>Go to next step</h2>
				<p>Tap on the screen to go to the next step.</p>
				<br>
				<br>
				<div align='right'> Next >>> </div>
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
        
<?php
    include 'footer.php';
?>