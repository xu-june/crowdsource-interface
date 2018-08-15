<?php 
    session_start();
    include 'connectDB.php';
    include 'header.php';
    savePageLog($_SESSION['pid'], "test1");
    
    // count files already uploaded
    $upload_cnt = 0;
    $obj_cnt = array(0, 0, 0);
    $imgPath = 'images/p' . $_SESSION['pid'] . '/t' .$_SESSION['trial'] .'/test1/';
    for ($i=0; $i<count($_SESSION["object_names"]); $i++) {
        $obj = $_SESSION["object_names"][$i];
        $files = glob($imgPath . $obj . "/*.png");
        $upload_cnt += count($files);
        $obj_cnt[$i] = count($files);
    }
?>

<!-- Uploads images to "test1" folder in server -->
<!-- PHP code adapted from http://php.net/manual/en/features.file-upload.php and https://gist.github.com/projectxcappe/1220777/9ec6a7e62fb9d7c9a93bd834fb434d7ae25ed6if5 -->

<!DOCTYPE html>
<html>
<head>
    <?php printMetaInfo(); ?>
    <title>Test 1</title>

    <script type="text/javascript">
        var upload_cnt = <?=$upload_cnt?>;
        var clickable = true;
        var obj_index = [<?=implode(',', $_SESSION['test1_order'])?>];
        var obj_names = ["<?=implode('","', $_SESSION['object_names'])?>"];
        var obj_counts = [<?=implode(',', $obj_cnt)?>];
        var bgColors = ['#76D7C4', '#FAD7A0', '#D7BDE2'];
        
        //this function works without ajax so that it synchronizes with the change of the counter displayed on the screen.
        function get_random_object() {
            $objects = $("#objects");
            if (upload_cnt >= <?=$_SESSION['test_img_num']*3?>) {
                $objects.text("Tap on the 'Next' button");
                return;
            }
            
            $objects.text(obj_names[obj_index[upload_cnt]-1]);
            document.getElementById("videoElement").style.borderColor = document.getElementById("objects").style.backgroundColor = bgColors[obj_index[upload_cnt]-1];
            show_prev_image(upload_cnt);
        }
        
        function captureImage() {
            if (!clickable) return;
            
            clickable = false;
            obj_counts[obj_index[upload_cnt]-1]++;
            upload_cnt++;
            get_random_object();
            show_prev_image(upload_cnt);
            $output = $("#output");
            $output.empty();
            
            
            var img = document.createElement("img");
            img.height = video.videoHeight/4;
            img.width = video.videoWidth/4;
            img.src = canvas.toDataURL();
            
            $.ajax({
              type: "POST",
              url: "upload.php",
              data: { 
                 imgBase64: img.src,
                 phase: 'test1',
                 obj_count: obj_counts[obj_index[upload_cnt-1]-1],
                 objectname: obj_names[obj_index[upload_cnt-1]-1]
              },
              success: function (data) {
                console.log('success'+data);

                $output = $("#output");
                $output.prepend(data);
                
                
                if (upload_cnt >= <?=$_SESSION['test_img_num']*3?>) {
                    $("#nextButton").show();
                    document.getElementById("nextButton").scrollIntoView();
                    return;
                } else {
                    document.getElementById("output").scrollIntoView();
                    clickable = true;
                }
                
              },
              error: function () { console.log('fail'); }
            }).done(function(o) {
              console.log('done'); 
            });
        }
        
        function show_prev_image(index) {
            if (index <= 0) return;
            
            console.log('set prev img - ' + index);
            $("#count").text(15-index);
            
            var video=document.querySelector('#videoElement');
            var canvas=document.querySelector('canvas');
            var context=canvas.getContext('2d');
            var w,h,ratio;
            ratio = video.videoWidth/video.videoHeight;
            w = video.videoWidth-100;
            h = parseInt(w/ratio,10);
            
            canvas.width = w;
            canvas.height = h;
            context.fillRect(0,0,w,h);
            context.drawImage(video,0,0,w,h);
        }
        
        // Refreshes bottom portion of the page to upload images
        $(document).ready(function () {
            get_random_object();
            $("#nextButton").hide();
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
        <?php printProgressBar(11); ?>
    
    <h3>Let's see how well you did!</h3>    
    <p>Tap on the camera stream to take a picture and see how well you did.</p>

    <h4><div id="objects" align='center'>
        </div></h4>
        
        <div align='center' style='position:relative;'>

            <video autoplay="true" onclick="captureImage()" control="true" id="videoElement" width="100%" playsinline></video><br>
            <div class="mb-3 ml-3" style="width:20vw;height:20vw;position:absolute;bottom:10px;">
                <div align='right'>
                    <span id='count' class='circle'>15</span>
                </div>
            
                <canvas id="canvas" style="width:18vw;height:18vw;background-color: gray;"></canvas>
            </div>
        
        </div>
        <div id="output" style='background-color:#00FFF0;' align='center'></div>
            
        <div align='right'>
            <button type="button" id='nextButton' class="btn btn-default" onclick="window.location.href='feedbackscreen1.php'">Next ></button>
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

</body>
</html>
           
<?php
    include 'footer.php';
?>
