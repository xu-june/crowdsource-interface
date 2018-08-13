<?php
    session_start();
    include 'connectDB.php';
    include 'header.php';
    savePageLog($_SESSION['pid'], "train2");
    
    // count files already uploaded
    $upload_cnt = 0;
    $obj_cnt = array(0, 0, 0);
    $imgPath = 'images/p' . $_SESSION['pid'] . '/t' .$_SESSION['trial'] .'/train2/';
    for ($i=0; $i<count($_SESSION["object_names"]); $i++) {
        $obj = $_SESSION["object_names"][$i];
        $files = glob($imgPath . $obj . "/*.png");
        $upload_cnt += count($files);
        $obj_cnt[$i] = count($files);
        //echo $obj."...".$obj_cnt[$i]."<br>";
    }
?>

<!-- Uploads images to "train2" folder in server -->
<!-- PHP code adapted from http://php.net/manual/en/features.file-upload.php and https://gist.github.com/projectxcappe/1220777/9ec6a7e62fb9d7c9a93bd834fb434d7ae25ed6if5 -->
        
<!DOCTYPE html>
<html>
<head>
    <title>Training 2</title>
    <?php printMetaInfo(); ?>

    <script type="text/javascript">
        var bgColors = ['#76D7C4', '#FAD7A0', '#D7BDE2'];
        var upload_cnt = <?=$upload_cnt?>;
        var obj_index = [<?=implode(',', $_SESSION['train2_order'])?>];
        var obj_names = ["<?=implode('","', $_SESSION['object_names'])?>"];
        var clickable = true;
        
        function get_random_object() {
            $objects = $("#objects");
            if (upload_cnt >= <?=$_SESSION['training_img_num']*3?>) {
                clickable = false;
                window.location.href='training2_subset20.php';
            }
            
            var step = Math.floor(upload_cnt/30);
            var obj_name = obj_names[obj_index[step]-1];
            
            $objects.text(obj_name);
            document.getElementById("objects").style.backgroundColor = bgColors[step];
        }
        
        function captureImage() {
            if (!clickable) return;
            
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
            
            upload_cnt++;
            show_prev_image(upload_cnt % 30);
            
            var step = Math.floor((upload_cnt-1)/30);
            var obj_name = obj_names[obj_index[step]-1];
            
            console.log(upload_cnt + ", " + step + ", " + obj_index[step]);
            
            $.ajax({
              type: "POST",
              url: "upload.php",
              data: { 
                 imgBase64: img.src,
                 phase: 'train2',
                 objectname: obj_name,
                 obj_count: upload_cnt%30+1
              },
              success: function (data) {
                console.log('success'+data);
                
                if (upload_cnt >= <?=$_SESSION['training_img_num']?>) {
                    get_random_object();
                }
              },
              error: function () { console.log('fail'); }
            }).done(function(o) {
              console.log('done'); 
            });
        }
        
        function clearCanvas(cnt) {
            var canvas=document.querySelector('canvas');
            var context=canvas.getContext('2d');
            context.clearRect(0, 0, canvas.width, canvas.height);
            $("#count").text(30-cnt);
        }
        
        function show_prev_image(index) {
            console.log('set prev img - ' + index);
            $("#count").text(30-index);
            
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
            show_prev_image(upload_cnt % 30);
        });
    </script>
</head>
<body>
    <div class="mt-3 mb-3 mr-3 ml-3">
        <?php printProgressBar(14); ?>

        <h3>Add your own objects (part 2)!</h3>
        <p>Like you did before, take 30 pictures of the following object by tapping on the camera stream.</p>

        <h4><div id="objects" align='center'>
        </div></h4>
        
        <div id='videoContainer' align='center' style='position:relative;'>
            <video id='videoElement' autoplay="true" onclick="captureImage()" control="true" width="100%" playsinline></video><br>
            
            <div class="fixed-bottom mb-3 ml-3" style="width:20vw;height:20vw;position:absolute;bottom:10px;">
                <div align='right'>
                    <span id='count' class='circle'>30</span>
                </div>
            
                <canvas id="canvas" style="width:18vw;height:18vw;background-color: gray;"></canvas>
                <!--<div id='prev_img' style="width:18vw;height:18vw;background-color: gray; align='center'">No image</div>-->
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

