<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <script src="./js/jquery-3.3.1.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>

    <title>Let's test the object recognizer!</title>
  </head>
  <body>
    <script>
        function take_picture() {
            document.getElementById("current_img").style.backgroundImage = "url('images/dummy.jpg')";
            document.getElementById("current_img").style.backgroundSize = "cover";
            document.getElementById("current_img").innerHTML = "";
            
            document.getElementById("rec_result").style.display = "block";
        }
    </script>
    <div class="mt-3 mb-3 mr-3 ml-3">
        <div class="progress mb-3">
          <div class="progress-bar" role="progressbar" style="width: 60%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">6 / 10</div>
        </div>
        
        <div>
            <h3>Let's test the trained object recognizer! </h3>
        </div>
        <p><small> You will go to the next step once you've taken 5 images total for each object. </small></p>
            
        <div style="height:180px;">
            <center> 
            <p>1. Upload an image of your <font color=red>Coke</font> (0/5) </p>
            
            <!-- replace this part with image once the participant takes a picture -->
            <div id="current_img" style="width:100px;height:100px;background-color:#eee;text-align:center;line-height:100px;"> No image </div> 
            
            <button type="button" class="btn btn-primary" onclick="take_picture();">Take a picture</button>
            </center>
        </div>
        <br>
        
        
        <!-- This part should appear after the participant takes a picture -->
        <div id="rec_result" style="height:100px;display:none;">
            <center> 
            
            <!-- replace this part with image once the participant takes a picture -->
            <p class="bg-warning"><i>The object is not recognized</i></p>
            
            <button type="button" class="btn btn-default" onclick="window.location.href='training2.php'">Next</button>
            </center>
        </div>
        
    </div> 
  </body>
</html>