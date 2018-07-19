<!DOCTYPE html>
<html>
<head>
    <title>Draft of Object Assignment</title>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link type="text/css" rel="stylesheet" href="screenformatting.css">

    <script type="text/javascript">
        function submit() {
            document.getElementById("submitbtn").click();
        }

        $('.submitbtn').click(function() {

           $.ajax({
              type: "get",
              url: "objects.php",
              data: { name: "John" }
          }).done(function( msg ) {
              alert( "Data Saved: " + msg );
          });    

      });
    </script>
</head>
<body>
    <h1>Number your objects!</h1>
    <p>Assign your objects to Object 1, Object 2, and Object 3.</p>

    <form name="form" action="" method="get">
        <p>Object 1: <input type="text" name="obj1" placeholder="Name of object 1 (e.g., Coke)"></p>
        <p>Object 2: <input type="text" name="obj2" placeholder="Name of object 2 (e.g., Pepsi"></p>
        <p>Object 3: <input type="text" name="obj3" placeholder="Name of object 3 (e.g., Sprite)"></p>
        <input type="submit" value="Submit" name="submit" id="submitbtn" style="display: none;">
        <p><button type="button" onclick="submit()">Submit</button></p>
        <div>
            <?php 

            // Configuring errors
            ini_set('display_errors',1);
            error_reporting(E_ALL);
            // var_dump($_FILES); 

            $obj1 = $_GET["obj1"];
            $obj2 = $_GET["obj2"];
            $obj3 = $_GET["obj3"];
            // /home/ubuntu/userinterface
            $url1 = "/var/www/html/Screens/image-folders" . $obj1;
            $url2 = "/var/www/html/Screens/image-folders" . $obj2;
            $url3 = "/var/www/html/Screens/image-folders" . $obj3;
            // echo $folderurl;\

            if (!mkdir($url1) && !mkdir($url2) && !mkdir($url3))
            {
                echo("Folders not created");
            }

            ?>
        </div>
    </form>

    <p>
        <button type="button" onclick="window.location.href='http://ec2-18-221-159-134.us-east-2.compute.amazonaws.com/Screens/screen2.html'">Next</button>
    </p>

</body>
</html>