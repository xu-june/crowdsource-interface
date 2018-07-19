<!-- Uploads images to "initialtest" folder in server -->
<!-- PHP code adapted from http://php.net/manual/en/features.file-upload.php and https://gist.github.com/projectxcappe/1220777/9ec6a7e62fb9d7c9a93bd834fb434d7ae25ed6f5 -->

<!DOCTYPE html>
<html>
<head>
    <title>Testing Screen 3</title>
    <!-- <script type="text/javascript" src="randomizeTest.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

    <script>

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
                    // alert("File uploaded");
                    $("#images").load("showimages.php");              
              }
          });
          });
        });

    </script>

</head>
<body>

    <h1>Let's test our system!</h1>
    <p>Your instructions are simple: Take a picture of the requested object! Click "Get Object" each time to see which object to use, then click "Upload" to send in your picture. The "Get Object" will disappear once you've taken 5 images total for each object.</p>
    <p>(Here's a hint: don't be scared if the object doesn't change! It's randomized, so if you've clicked the button and it doesn't change, take another picture and send it in.)</p>
    
    <button type="button" id="objButton" onclick="randomize()">Get Object</button>

    <!-- <p id="object"></p> -->
    <p id="obj1" hidden="true">Object 1</p>
    <p id="obj2" hidden="true">Object 2</p>
    <p id="obj3" hidden="true">Object 3</p>

    <p id="buttoncount"></p>
    <p id="function"></p>

    <!-- <div id="images"> -->
        <form action="upload.php" method="post" enctype="multipart/form-data">
            Take an image to train:
            <input type="file" style="display: none;" accept="image/*" capture="camera" name="fileToUpload" id="fileToUpload" required="true">
            <button type="button" onclick="takePic()">Take a Picture</button>
            <input type="submit" value="Upload Image" name="submit">
        </form>
    <!-- </div> -->

    <div id="images"></div>

    <p>[Feedback from object recognizer will go here]</p>

    <button type="button" onclick="window.location.href='http://ec2-18-221-159-134.us-east-2.compute.amazonaws.com/Screens/screen4.html'">Next page</button>

    <script type="text/javascript">
        var obj1 = {"id":"obj1","count":"0"};
        var obj2 = {"id":"obj2","count":"0"};
        var obj3 = {"id":"obj3","count":"0"};
        // var obj1 = {"id":"object1","count":"0"};
        // var obj2 = {"id":"object2","count":"0"};
        // var obj3 = {"id":"object3","count":"0"};
        var button = {"id":"objButton","count":"0"};
        var arr = [obj1, obj2, obj3];

        // For "Get Object" button; selects a random object for user to test
        function randomize() {

            button.count++;
            document.getElementById("buttoncount").innerHTML = button.count;
            // Hides the previous object
            for (var i = 0; i < arr.length; i++) {
                document.getElementById(arr[i].id).style.display = "none";
            }
            var randObj = arr[Math.floor(Math.random() * arr.length)];
            // Ensures each object can only be called 5 times
            while (randObj.count + 1 > 5) {
                randObj = arr[Math.floor(Math.random() * arr.length)];
            }
            randObj.count++;
            
            // document.getElementById("object").innerHTML = document.getElementById(randObj.id).value;
            document.getElementById(randObj.id).style.display = "block";

            // Hides the button after all objects are called 5 times
            if (button.count >= 15) {
                document.getElementById(button.id).style.display = "none";
            }

        }

        // For "Take a Picture" button
        function takePic() {
            document.getElementById("fileToUpload").click();
        }

    </script>

</body>
</html>
