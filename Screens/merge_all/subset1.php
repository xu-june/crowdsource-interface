<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <script src="./js/jquery-3.3.1.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>

    <title>Subset selection</title>
  </head>
  <body style="height:100%">
      <script>
            function load_images() {
                for (var i=0; i<30; i++){
                    document.getElementById("set_view").innerHTML += "<div class='ml-1 mr-1' style='display:inline-block;'><div style=\"width:100px;height:100px;background-image:url(\'images/dummy.jpg\');background-size:cover;\"></div>haha"+(i+1)+"</div>";
                }
            }
        </script>
    <div class="mt-3 mb-3 mr-3 ml-3" style="height:100%">
        <div class="progress mb-3" style="">
          <div class="progress-bar" role="progressbar" style="width: 70%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">5 / 7</div>
        </div>
        
        <div>
            <h2> Select the 20 best images out of the 30 you took. </h2>
            <p><small> A green border will appear around the images you select. </small></p>
        </div>
            
        <div id='set_view' align="center">
            
        </div>
        <br>
        <div align="center">
            <p> 0 / 30 selected </p>
            
            <!-- the button should appear after the participant selects 20, 5, 1 samples. -->
            <button type="button" class="btn btn-default" onclick="window.location.href='test2.php'">Next</button>
        </div>
        <script>
            load_images();
        </script>
    </div> 
  </body>
</html>