<?php
    session_start();
    include 'connectDB.php';
    include 'header.php';
	savePageLog($_SESSION['pid'], basename($_SERVER['PHP_SELF']));
    
    // get state variables from database
    $query = "SELECT phase, upload_cnt_obj1, upload_cnt_obj2, upload_cnt_obj3, subset_cnt_obj, subset_cnt_num FROM "
        ."variables where participant_id=".$_SESSION['pid']." and trial = ".$_SESSION['trial']." order by time desc";
    $latestVar = getSelect($query);
    
    $phase = $latestVar['phase']; 
    $obj_index = -1;
    $obj_name = 'na';
    $subset_for = 'na';
    $selected_str = 'na';
    if (strpos($phase, 'test') === 0) {
        $total_cnt = $latestVar['upload_cnt_obj1']+$latestVar['upload_cnt_obj2']+$latestVar['upload_cnt_obj3'];
        
        $obj_index = getObjectIndex($phase, $total_cnt);
        $obj_name = $_SESSION["object_names"][$obj_index-1];
        
        if ($phase == 'test0')
            $_SESSION['progress'] = 7;
        else if ($phase == 'test1')
            $_SESSION['progress'] = 7;
        else if ($phase == 'test2')
            $_SESSION['progress'] = 7;
        
    } else if (strpos($phase, 'train') == 0 && strpos($phase, 'question') == false){
        $total_cnt = $latestVar['upload_cnt_obj1']+$latestVar['upload_cnt_obj2']+$latestVar['upload_cnt_obj3'];
        
        $obj_index = getObjectIndex($phase, $total_cnt);
        $obj_name = $_SESSION["object_names"][$obj_index-1];
        
        if ($phase == 'train1')
            $_SESSION['progress'] = 9;
        else if ($phase == 'train2')
            $_SESSION['progress'] = 7;
        else if ($phase == 'test2')
            $_SESSION['progress'] = 7;
    } else if (strpos($phase, 'subset') === 0){
        $subset_for = 'train1';
        $subset_for_num = 1;
        if (strpos($phase, 'train1') === false) {
            $subset_for = 'train2';
            $subset_for_num = 2;
        }
        
        $obj_index = $_SESSION['order'][$subset_for][$latestVar['subset_cnt_obj']];
        $obj_name = $_SESSION['object_names'][$obj_index - 1];
        
        if ($latestVar['subset_cnt_num'] != 0) {
			// get state selected objects from database
			$query = "SELECT `subset".$subset_for_num."_".$obj_index."_".$latestVar['subset_cnt_num']."` FROM "
				."Objects where participant_id=".$_SESSION['pid']." and trial = ".$_SESSION['trial']."";
			//echo $query;
			$subset_record = getSelect($query);
			$selected_str = $subset_record["subset".$subset_for_num."_".$obj_index."_".$latestVar['subset_cnt_num']];
        }
    } else {
        $obj_index = 0;
        $obj_name = $_SESSION["object_names"][0];
    }
    
    $_SESSION['phase'] = $phase;
    //echo implode(", ", $_SESSION['order'][$phase])."<br>".implode(", ", $_SESSION['object_names']);
?>

<!doctype html>
<html lang="en">
  <head>
  
    <?php printMetaInfo(); ?>
    <title>
    	User study on training object recognizer
    </title>
    
    <script type="text/javascript">
        test_img_num = <?=$_SESSION['test_img_num']?>;
        training_img_num = <?=$_SESSION['training_img_num']?>;
        phase = '<?=$latestVar['phase']?>';
        upload_cnt_obj1 = <?=$latestVar['upload_cnt_obj1']?>;
        upload_cnt_obj2 = <?=$latestVar['upload_cnt_obj2']?>;
        upload_cnt_obj3 = <?=$latestVar['upload_cnt_obj3']?>;
        subset_cnt_obj = <?=$latestVar['subset_cnt_obj']?>;
        subset_cnt_num = <?=$latestVar['subset_cnt_num']?>;
        obj_name = '<?=restoreSpecial($obj_name)?>';
        obj_index = <?=$obj_index?>;
        subset_for = '<?=$subset_for?>';
        label = '';
        selected_str = '<?=$selected_str?>';
        trial = <?=$_SESSION['trial']?>;
        
        var i=0;
        for(i=0; i<30; i++){
        	selected.push(false);
        }
        
        if (selected_str != 'na') {
        	var ids = selected_str.split(":");
        	for(i=0; i<ids.length; i++){
        		selected[parseInt(ids[i])-1] = true;
        	}
        }
        // Refreshes bottom portion of the page to upload images
        $(document).ready(function () {
        	videoElement = document.getElementById("videoElement");
            
            videoElement.onloadedmetadata = function() {
            	clickable = true;
		        createOffscreenCanvas(videoElement);	
		        
	            update_interface();
			};
        });
        
        window.onerror = function (msg, url, lineNo, columnNo, error) {
          //alert(msg+"--"+url+"--"+lineNo+"--"+columnNo+"--"+error);
          $("#errorDiv").append("<br><br><br><br><br><br>"+msg+"--"+url+"--"+lineNo+"--"+columnNo+"--"+error);

          return false;
        };
        
        if (window.history && history.pushState) {
			addEventListener('load', function() {
				history.pushState(null, null, null); // creates new history entry with same URL
				addEventListener('popstate', function() {
					var stayOnPage = confirm("You are not allowed to go back. All of your data will be lost if you go back. Press 'OK' if you still want to go back, 'Cancel' if you want to stay.");
					if (!stayOnPage) {
						history.pushState(null, null, null);
					} else {
						history.back();
					}
				});    
			});
		}
        //window.onbeforeunload = function() { return "You are not allowed to go back. All of your data will be lost if you press back button. You may not be compensated if you press back button."; };
        
        function test(){
            window.location.replace("safari://path/");
        }
    </script>
  </head>
	<body>
		<div class="mt-3 mb-3 mr-3 ml-3">
            <div id='msgArea'></div>
            <div id='taskArea'>
                <div align='center' style='position:relative;' id='video_interface'>
                    <h4><div id="objects" align='center'>
                    </div></h4>

                    <video autoplay="true" onclick="captureImage()" control="true" id="videoElement" width="100%" playsinline muted></video><br>
                    <!--<video onclick="captureImage()" id="videoElement" width="100%" autoplay muted playsinline></video><br>-->
                    <div id='preview' style="position:absolute;bottom:0px;left:0px;">
                        <div id='count_container' align='right'>
                            <div id='count' class='numCircle'>15</div>
                        </div>
                    
                        <canvas id="canvas" style="background-color: black;"></canvas>
                    </div>
                    
                    <div id='prediction' style="width:100%;height:1vh;position:absolute;top:5vh;display:none;filter:alpha(opacity=70)" align='center'>
                        <!--<div class="bg-dark text-white"> Recognition result: </div> -->
                        <div class="bg-dark text-white pt-2 pb-2" id='label'>Recognition is going on...</div>
                    </div>
                </div>
                
                <canvas id="hiddenCanvas" style="position: absolute; z-index: -1"></canvas>
                <!--
                <div align='right'>
                    <button type="button" id='nextButton' class="btn btn-default" onclick="window.location.href='before_training1.php'" style="display:none;">Next ></button>
                </div>
                -->
            </div>
            <div id='subsetDiv'></div>
        </div>
              
        <div id='errorDiv'></div>
        
        
        <div class="overlay" id="start_overlay" onclick="start_overlay_off()">
        	<div class="overlay_contents mt-3 mb-3 mr-3 ml-3">
				<h2><span id='obj_name1'></span></h2>
				<p>Take 30 photos of <span id='obj_name2'></span> for robust object recognizer</p>
                
                <p>Tap on the screen to start.</p>
        	</div>
        </div>
        
        <div class="overlay" id="end_overlay" onclick="goToNext(phase+'_question');end_overlay_off();">
        	<div class="overlay_contents">
				<h2>Go to next step</h2>
				<p>You finished training the object recognizer. Tap on the screen to go to the next step.</p>
				<br>
				<br>
				<div align='center'> Next </div>
        	</div>
        </div>
        
        <device type="media" onchange="update(this.data)"></device>
		<video autoplay></video>
		<script>
		  function update(stream) {
			document.querySelector('video').src = stream.url;
		  }
		</script>
        
        <script>
             var video = document.querySelector("#videoElement");
            const constraints = {
                advanced: [{
                    facingMode: "environment"
                }]
            };    
            
            navigator.getUserMedia = ( navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia);// || navigator.mediaDevices.getUserMedia);
//          alert(navigator.getUserMedia);
            
            
            navigator.mediaDevices.getUserMedia({
                video: constraints
            }).then((stream) => {
                video.srcObject = stream;
            }).catch(function(err0r) {
                console.log("Something went wrong!");
              });   
            
              
            console.log('<?php echo $_SESSION['pid']."_".$_SESSION['pcode']."_".$_SESSION['trial']?>');   
        </script>
  </body>
</html>