<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <script src="./js/jquery-3.3.1.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>

    <title>Add your own objects!</title>
  </head>
  <body>
    <script>
    	var cnt = 0
        function take_picture() {
            document.getElementById("current_img").style.backgroundImage = "url('images/dummy.jpg')";
            document.getElementById("current_img").style.backgroundSize = "cover";
            document.getElementById("current_img").innerHTML = "";
        }
        function add_image() {
	        cnt += 1;
            console.log('haha');
            document.getElementById("set_view").innerHTML += "<div class='ml-1 mr-1' style='display:inline-block;'><div style=\"width:100px;height:100px;background-image:url(\'images/dummy.jpg\');background-size:cover;\"></div>Image"+cnt+"</div>";
        }
    </script>
    <div class="mt-3 mb-3 mr-3 ml-3">
        <div class="progress mb-3">
          <div class="progress-bar" role="progressbar" style="width: 40%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">4 / 10</div>
        </div>
        
        <div>
            <h3> Let's train the object recognizer with your objects! </h3>
        </div>
        <p><small> Take a picture with <b>'take a picture'</b> button and add it to training set with <b>'add picture'</b> button. You need 30 pictures for training. </small></p>
            
        <div style="height:180px;">
            <center> 
            <div>
            <p>1. Add images of your <font color=red>Coke</font> (0/30) </p>
            
            <!-- replace this part with image once the participant takes a picture -->
            <div id='current_img' style="width:100px;height:100px;background-color:#eee;text-align:center;line-height:100px;"> No image </div> 
            
            <button type="button" class="btn btn-primary" onclick="take_picture();">Take a picture</button>
            <button type="button" class="btn btn-primary" onclick="add_image();">Add picture</button>
            </center>
        </div>
        <br>
        
        <div id='set_view' class="pl-1 pr-1" style="max-width:none;overflow-x:scroll;" align="center">
        </div>
        
        <center>
        <button type="button" class="btn btn-default" onclick="window.location.href='subset1.php'">Next</button> 
        </center>
    </div> 
  </body>
</html>