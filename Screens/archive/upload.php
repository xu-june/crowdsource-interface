<!-- Uploads images to "uploads" folder in server -->
<!-- PHP code adapted from http://php.net/manual/en/features.file-upload.php and https://gist.github.com/projectxcappe/1220777/9ec6a7e62fb9d7c9a93bd834fb434d7ae25ed6f5 -->

<!-- <!DOCTYPE html>
<html>
<head>
    <title>Camera and Upload</title>
    <link type="text/css" rel="stylesheet" href="camera_upload.css">
</head>
<body>

    <form action="upload.php" method="post" enctype="multipart/form-data">
        Take an image to train:
        <input type="file" style="display: none;" accept="image/*" capture="camera" name="fileToUpload" id="fileToUpload" required="true">
        <button type="button" onclick="takePic()">Take a Picture</button>
        <input type="submit" value="Use Image" name="submit">
    </form> -->

    <!-- <p id="buttonCount"></p>

    <script type="text/javascript">
        // var count = 0;
        // For "Take a Picture" button
        function takePic() {
            document.getElementById("fileToUpload").click();
        }

        // function countClick() {
        //     count++;
        //     document.getElementById("buttonCount").innerHTML = count;
        // }

    </script>

</body>
</html> -->

<?php 

// Configuring errors
// ini_set('display_errors',1);
// error_reporting(E_ALL);
// var_dump($_FILES); 
// if (isset($_FILES['fileToUpload']['error']) && $_FILES['fileToUpload']['error']!=UPLOAD_ERR_NO_FILE) {

    try {
        
        // Undefined | Multiple Files | $_FILES Corruption Attack
        // If this request falls under any of them, treat it invalid.
        if (
            !isset($_FILES['fileToUpload']['error']) ||
            is_array($_FILES['fileToUpload']['error'])
        ) {
            // 'Invalid parameters.'
            throw new RuntimeException('Invalid parameters.');
        }

        // Check $_FILES['upfile']['error'] value.
        switch ($_FILES['fileToUpload']['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                // 'No file sent.'
                throw new RuntimeException('No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                // 'Exceeded filesize limit.'
                throw new RuntimeException('Exceeded filesize limit.');
            default:
                throw new RuntimeException('Unknown errors.');
        }

        // You should also check filesize here. 
        if ($_FILES['fileToUpload']['size'] > 2000000000000000) {
            // 'Exceeded filesize limit.'
            throw new RuntimeException('Exceeded filesize limit.');
        }

        // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
        // Check MIME Type by yourself.
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (false === $ext = array_search(
            $finfo->file($_FILES['fileToUpload']['tmp_name']),
            array(
                'jpg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
            ),
            true
        )) {
            // 'Invalid file format.'
            throw new RuntimeException('Invalid file format.');
        }

        // You should name it uniquely.
        // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
        // On this example, obtain safe unique name from its binary data.
        $filename = sha1_file($_FILES['fileToUpload']['tmp_name']);
        if (!move_uploaded_file(
            $_FILES['fileToUpload']['tmp_name'],
            sprintf('./initialtest/%s.%s', $filename, $ext)
        )) {
            // 'Failed to move uploaded file.'
            throw new RuntimeException('Failed to move uploaded file.');
        }

        echo "File was uploaded successfully.";
    //     echo '<p></p>';
        // Displaying the images
        // $files = glob("initialtest/*.jpg");
        // for ($i=0; $i<count($files); $i++)
        // {
        //     $num = $files[$i];
        //     echo '<img src="'.$num.'" alt="random image" width="150" height="150">'."&nbsp;&nbsp;";
        // }

    } catch (RuntimeException $e) {

        echo $e->getMessage();

    }

// } else {
//     echo 'No image was taken, please try again!';
// }

?>
