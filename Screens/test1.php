<?php 
    session_start();
    include 'connectDB.php';
    include 'header.php';
    savePageLog($_SESSION['pid'], "test1");
?>

<!-- Uploads images to "test1" folder in server -->
<!-- PHP code adapted from http://php.net/manual/en/features.file-upload.php and https://gist.github.com/projectxcappe/1220777/9ec6a7e62fb9d7c9a93bd834fb434d7ae25ed6if5 -->

<!DOCTYPE html>
<html>
<head>
    <?php printMetaInfo(); ?>
    <title>Test 1</title>

    <script type="text/javascript">
        function get_random_object() {
            $.ajax({
              type: "POST",
              url: "get_random_object.php",
              data: { 
                 phase: 'test1'
              },
              success: function (data) {
                console.log('random object: '+data);

                $objects = $("#objects");
                $objects.empty();
                
                if (data == 'this step is done') {
                    window.location.href='feedbackscreen1.php';
                } else {
                    var words = data.split(' ');
                    var objectname = words[0];
                    var objectname_space = objectname.replace(/_/g,' ');
                    var count = words[1];
                    
                    $objects = $("#objects");
                    $objects.empty();
                    $objects.append(objectname + " ("+count+" / 5)");
                    
                    // remove previous result     
                    $output = $("#output");
                    $output.empty();            
                    $rec_result = $("#rec_result");
                    $rec_result.empty();
                    $("#nextButton").hide();
                    // $("#takeButton").show();
                    $("#result").hide();
                    
                    // show modal
                    $modalLabel = $("#guideBody");
                    $modalLabel.empty();
                    $modalLabel.append("<h1 class='bg-warning' align='center'>" +objectname_space + " ("+count+" / 5)</h1>"
                                    +"Bring your "+objectname_space+" to take a picture.");
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
            // $("#takeButton").hide();
            $("#result").show();
            
            $.ajax({
              type: "POST",
              url: "upload.php",
              data: { 
                 imgBase64: img.src,
                 phase: 'test1',
                 objectname: $("#objects").text().split(" ")[0]
              },
              success: function (data) {
                console.log('success'+data);
                $rec_result = $("#rec_result");
                $rec_result.empty();
                $rec_result.append(data);
                
                $("#nextButton").show();
              },
              error: function () { console.log('fail'); }
            }).done(function(o) {
              console.log('done'); 
            });
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
        <?php printProgressBar(6); ?>
    
    <h3>Let's see how well you did!</h3>    
    <p>Take a picture of the requested object to see how well you did. Again, click <i class="buttonname">Get Object</i> to know which one to take a picture of, then <i class="buttonname">Upload</i> to upload it. You'll be done when <i class="buttonname">Get Object</i> disappears.</p>

     <h4><div id="objects" class="bg-warning" align='center'>
        </div></h4>
        
        <div align='center' style='display:inline-block;'>

            <video autoplay="true" onclick="captureImage()" control="true" id="videoElement" width="100%" playsinline></video><br>
            <!-- <p><button type="button" id='takeButton' class="btn btn-primary" onclick="captureImage()">Take</button></p> -->

            <div id='result' class="card border-success mt-3 mb-3">
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
            <button type='button' id='nextButton' class='btn btn-default' onclick='get_random_object();' style="display:none;">Next</button>
        </div>
        
        <br>
        
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

</body>
</html>
           
<?php
    include 'footer.php';
?>
