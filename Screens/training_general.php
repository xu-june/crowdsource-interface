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
    	//echo $obj."...".$obj_cnt[$i]."<br>";
    }
    $_SESSION['step'] = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Training images</title>
    <?php printMetaInfo(); ?>

    <script type="text/javascript">
        var bgColors = ['#76D7C4', '#FAD7A0', '#D7BDE2'];
        var upload_cnt = <?=$upload_cnt?>;
        var obj_index = [<?=implode(',', $_SESSION['order'][$phase])?>];
        var obj_names = ["<?=implode('","', $_SESSION['object_names'])?>"];
        var clickable = false;
        var training_img_num = <?=$_SESSION['training_img_num']?>;;
        
        // Refreshes bottom portion of the page to upload images
        $(document).ready(function () {
        	videoElement = document.getElementById("videoElement");
            get_random_object_train(training_img_num);
            
			videoElement.onloadedmetadata = function() {
				clickable = true;
	        	createOffscreenCanvas();
				document.getElementById('interface').scrollTop = 10;
	            document.getElementById("interface").scrollIntoView(true);
			};
			
			$("#count").text(training_img_num-upload_cnt % training_img_num);
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
        
        <div id='interface' align='center' style='position:relative;'>
			<h4><div id="objects" align='center'>
			</div></h4>
			
            <video id='videoElement' autoplay="true" onclick="captureImage_train(training_img_num, '<?=$phase?>')" control="true" width="100%" playsinline></video><br>
            
			<div id='preview' class="fixed-bottom mb-3 ml-3" style="width:20vw;height:20vw;position:absolute;bottom:10px;">
				<div align='right'>
					<div id='count' class='numCircle'>30</div>
				</div>
			
				<canvas id="canvas" style="width:18vw;height:18vw;background-color:black;"></canvas>
				<!--<div id='prev_img' style="width:18vw;height:18vw;background-color: gray; align='center'">No image</div>-->
			</div>
        </div>
        
        
        <div class="overlay" id="start_overlay" onclick="start_overlay_off()">
        	<?php printProgressBar($progress); ?>
        	<div class="overlay_contents">
				<h2><span id='obj_name1'></span></h2>
				<p>Take 30 photos of <span id='obj_name2'></span> by tapping in the camera screen.</p>
				<p>Repeat until you take 30 photos of objects (5 for each).</p>
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