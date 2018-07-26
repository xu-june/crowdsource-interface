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
            // ini_set('display_errors',1);
            // error_reporting(E_ALL);
            // var_dump($_FILES); 

            $urlts = "/var/www/html/Screens/train1/";
            $urltr1 = "/var/www/html/Screens/train1/";
            $urltr2 = "/var/www/html/Screens/train2/";
            $urlts1 = "/var/www/html/Screens/test1/";
            $urlts2 = "/var/www/html/Screens/test2/";

            $url1_ts = $urlts . $_GET["obj1"];
            $url2_ts = $urlts . $_GET["obj2"];
            $url3_ts = $urlts . $_GET["obj3"];

            $url1_tr1 = $urltr1 . $_GET["obj1"];
            $url2_tr1 = $urltr1 . $_GET["obj2"];
            $url3_tr1 = $urltr1 . $_GET["obj3"];

            $url1_tr2 = $urltr2 . $_GET["obj1"];
            $url2_tr2 = $urltr2 . $_GET["obj2"];
            $url3_tr2 = $urltr2 . $_GET["obj3"];

            $url1_ts1 = $urlts1 . $_GET["obj1"];
            $url2_ts1 = $urlts1 . $_GET["obj2"];
            $url3_ts1 = $urlts1 . $_GET["obj3"];

            $url1_ts2 = $urlts2 . $_GET["obj1"];
            $url2_ts2 = $urlts2 . $_GET["obj2"];
            $url3_ts2 = $urlts2 . $_GET["obj3"];

            // echo $url1;
            // echo "<p></p>";
            // echo $url2;
            // echo "<p></p>";
            // echo $url3;

            if (mkdir($url1_tr1) && mkdir($url2_tr1) && mkdir($url3_tr1) && mkdir($url1_tr2) && mkdir($url2_tr2) && mkdir($url3_tr2) && mkdir($url1_ts1) && mkdir($url2_ts1) && mkdir($url3_ts1) && mkdir($url1_ts2) && mkdir($url2_ts2) && mkdir($url3_ts2) && mkdir($url1_ts) && mkdir($url2_ts) && mkdir($url3_ts))
            {
                echo("Folders created");
            }

            ?>
        </div>
    </form>

    <p>
        <button type="button" onclick="window.location.href='screen2.html'">Next</button>
    </p>

</body>
</html>