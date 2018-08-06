<!-- Uploads images to "test1" folder in server -->
<!-- PHP code adapted from http://php.net/manual/en/features.file-upload.php and https://gist.github.com/projectxcappe/1220777/9ec6a7e62fb9d7c9a93bd834fb434d7ae25ed6f5 -->

<?php 

// Configuring errors
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();
// var_dump($_FILES); 
// if (isset($_FILES['fileToUpload']['error']) && $_FILES['fileToUpload']['error']!=UPLOAD_ERR_NO_FILE) {

    try {

        // echo "<p></p>";
        $obj = $_SESSION['currObj'];
        // echo $folder . " is the folder";
        // echo "<p></p>";
        
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
        // NOTE: for testing
        $uuid = "12345"; 
        $phase = "test1";
        $dest_path = dirname(__FILE__) . '/images/' . $uuid . '/' . $phase . '/' . $obj . '/';
        $img_path = $dest_path . $filename . '.' . $ext;
        if (!move_uploaded_file(
            $_FILES['fileToUpload']['tmp_name'], $img_path)) {
            // 'Failed to move uploaded file.'
            throw new RuntimeException('Failed to move uploaded file.');
        }

        // echo "File was uploaded successfully.";
        require(dirname(__FILE__).'/../TOR/rest_client.php');
        // send an image to the server for testing
        $label = upload_and_test($uuid, $phase, $img_path);
        // return the testing result, label
        echo $label;

    } catch (RuntimeException $e) {

        echo $e->getMessage();

    }

?>
