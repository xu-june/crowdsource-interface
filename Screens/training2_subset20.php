<!-- CSS and jQuery adapted from http://www.prepbootstrap.com/bootstrap-template/image-checkbox -->
<!-- Takes images from train1 folder and saves selected filenames into $scr5_subselect20 -->
<?php
    session_start();
	include 'header.php';
    include 'connectDB.php';
    
    $phase = "subset2";
    $phase_for = "train2";
    $progress = 15;
    $subset_num = 20;
    $next = 'training2_subset5.php';
    savePageLog($_SESSION['pid'], $phase);
	
    include 'subset_general.php';
?>


