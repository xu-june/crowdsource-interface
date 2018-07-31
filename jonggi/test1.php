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
            
            document.getElementById("fileToUpload").click();
            document.getElementById("rec_result").style.display = "block";
        }
    </script>
    <div class="mt-3 mb-3 mr-3 ml-3">
        <div class="progress mb-3">
          <div class="progress-bar" role="progressbar" style="width: 30%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">3 / 10</div>
        </div>
        
        <div>
            <h3>Let's test the object recognizer! </h3>
        </div>
        <p><small> You will go to the next step once you've taken 5 images total for each object. </small></p>
            
        <div style="height:230px;">
            <center> 
            <p>1. Upload an image of your <font color=red>Coke</font> (0/5) </p>
            
            <!-- replace this part with image once the participant takes a picture -->
            <div id="current_img" style="width:150px;height:150px;background-color:#eee;text-align:center;line-height:150px;"> No image </div> 
            
            <button type="button" class="btn btn-primary" onclick="take_picture();">Take a picture</button>
			<input type="file" style="display: none;" accept="image/*" capture="camera" name="fileToUpload" id="fileToUpload" required="true">
            </center>
        </div>
        <br>
        
        
        <!-- This part should appear after the participant takes a picture -->
        <div id="rec_result" style="height:100px;display:none;">
            <center> 
            
            <!-- replace this part with image once the participant takes a picture -->
            <p class="bg-warning"><i>The object is not recognized</i></p>
            
            <p> Don't be scared if the object is not recognized.<br> It is because object recognizer is not trained with your object yet.</p>
            
            <button type="button" class="btn btn-default" onclick="window.location.href='training1.php'">Next</button>
            </center>
        </div>
        
    </div> 
    
    <script>
    
		document.getElementById("fileToUpload").onchange = function () {
			var reader = new FileReader();

			reader.onload = function (e) {
				// get loaded data and render thumbnail.
				//document.getElementById("image").src = e.target.result;
				document.getElementById("current_img").style.backgroundImage = "url('"+e.target.result+"')";
				document.getElementById("current_img").style.backgroundSize = "cover";
				document.getElementById("current_img").innerHTML = "";
			};

			// read the image file as a data URL.
			reader.readAsDataURL(this.files[0]);
		};
		
    </script>
  </body>
</html>