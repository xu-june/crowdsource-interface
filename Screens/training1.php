<?php
    include 'connectDB.php';
    include 'header.php';
    savePageLog($_SESSION['pid'], "train1");

    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    session_start();
    
    $_SESSION['phase'] = 'train1';

    // Gets array of objects and counts
    $objects = $_SESSION['objects_tr1'];

    $obj1 = $_SESSION['obj1'];
    $obj2 = $_SESSION['obj2'];
    $obj3 = $_SESSION['obj3'];
    $arrSize = count($_SESSION['subselectObj']);

    $randObj = "";
    // $numFiles = 0;

    // echo "<p></p>";
    // echo "count 1: " . $_SESSION['objects_ts0'][$obj1];
    // echo "<p></p>";
    // echo "count 2: " . $_SESSION['objects_ts0'][$obj2];
    // echo "<p></p>";
    // echo "count 3: " . $_SESSION['objects_ts0'][$obj3];
    // echo "<p></p>";

    // Randomizes object labels
    function randomize() {
        global $objects, $randObj, $obj1, $obj2, $obj3, $numFiles;
        // Ensures that this executes until all objects have been shown 5 times
        if ($_SESSION['objects_tr1'][$obj1] < 1 || $_SESSION['objects_tr1'][$obj2] < 1 || $_SESSION['objects_tr1'][$obj3] < 1) {
            $randObj = array_rand($objects, 1);
            // Ensures each object is called 5 times
            while ($objects[$randObj] >= 1) {
                $randObj = array_rand($objects, 1);
            }
            // Increases the count for the object
            $_SESSION['objects_tr1'][$randObj]++;
            // Sends object to upload file
            $_SESSION['currObj'] = $randObj;
            $_SESSION['subselectObj'][] = $randObj;
            // $numFiles = count(glob("images/12345/train1/".$randObj."/*.jpg"));
            return $randObj;
        }
    }

?>

<!-- Uploads images to "train1" folder in server -->
<!-- PHP code adapted from http://php.net/manual/en/features.file-upload.php and https://gist.github.com/projectxcappe/1220777/9ec6a7e62fb9d7c9a93bd834fb434d7ae25ed6if5 -->

<!DOCTYPE html>
<html>
<head>
    <title>Training 1</title>
    <?php printMetaInfo(); ?>

    <!-- <script>
        // Refreshes bottom portion of the page to upload and show images
        $(document).ready(function () {
            $('form').on('submit', function (e) {
              e.preventDefault();

            $.ajax({
                type: 'post',
                url: 'upload.php',
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function () {
                    $("#images").load("showimages.php");              
              }
          });
          });
        });
    </script> -->

    <script type="text/javascript">
        // For "Get Object" button; it disappears after three objects are shown
        function reload() {
            var size = "<?php echo $arrSize ?>";
            if (size < 2) {
                window.location.reload();
            }
            else {
                document.getElementById("objButton").style.display = "none";
                document.getElementById("nextbtn").style.display = "block";
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
            
            $rec_result = $("#rec_result");
            $rec_result.empty();
            
            $.ajax({
              type: "POST",
              url: "upload.php",
              data: { 
                 imgBase64: img.src,
                 filename: '<?php echo $_SESSION['pid']."_tmpObj_train1"?>'
              },
              success: function (data) {
                console.log('success'+data);
                $("#images").load("showimages.php");
                // $rec_result = $("#rec_result");
                // $rec_result.empty();
                // $rec_result.append(data);
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
        <?php printProgressBar(4); ?>

        <h3>Add your own objects!</h3>
        <p>Take 30 pictures of the requested object. Click on <mark>Get Object</mark>, then click <mark>Upload</mark> to upload your image of the object. The counter at the bottom will help you keep track of how many images you've uploaded.</p>
        <p>Once you complete 30 images for each object, click <mark>Get Object</mark>again to know what to train next! You'll know you're finished when <mark>Next</mark> appears.</p>

        <div align="center">
            <p><button type="button" class="btn btn-primary" id="objButton" onclick="reload()">Get Object</button></p>
        </div>

        <div id="objects" class="objects">
            <?php echo randomize(); ?>
        </div>
        
        <div align='center' style='display:inline-block;'>
            <video autoplay="true" control="true" id="videoElement" width="45%" playsinline></video><br>
            <button type="button" class="btn btn-primary" onclick="captureImage()">Take a Picture</button>

            <!-- <div class="card border-success mb-3">
              <div class="card-header">Result</div>
              <div class="card-body text-success">
                <div id="output" style='display:inline-block;'></div>
                <div id='rec_result' class="card-text" style='display:inline-block;'></div>
              </div>
            </div> -->
        </div>
        
        <br>

        <div id="images"></div>
        
        <button type="button" id="nextbtn" class="btn btn-default" onclick="window.location.href='training1_subset20.php'" style="display: none;">Next</button>
        
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
