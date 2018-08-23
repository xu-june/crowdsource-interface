
<?php 
    session_start();
    include 'connectDB.php';
    include 'header.php';
    $phase = "test0";
    $progress = 7;
    $next = 'before_training1.php';
    savePageLog($_SESSION['pid'], $phase);
    
    include 'test_general.php';
?>

<!-- Uploads images to "test0" folder in server -->
<!-- PHP code adapted from http://php.net/manual/en/features.file-upload.php and https://gist.github.com/projectxcappe/1220777/9ec6a7e62fb9d7c9a93bd834fb434d7ae25ed6if5 -->

