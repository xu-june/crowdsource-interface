<?php
	session_start();
	include 'connectDB.php';
	include 'header.php';
	savePageLog($_SESSION['pid'], basename($_SERVER['PHP_SELF']));

    if (!empty($_POST['bq4'])) {
        $q4 = $_POST["bq4"];
        $q5 = $_POST["bq5"];
        $q6 = $_POST["bq6"];
        $q7 = $_POST["bq7"];
        $sql = "UPDATE participant_info set `bq4`='".$q4."', `bq5`='".$q5."', `bq6`='".$q6."', `bq7`='".$q7."' WHERE `participant_id`=".$_SESSION['pid'].";";
        execSQL($sql);
    }
    //echo $_SESSION['pid']."-".$_SESSION['pcode']."-".$_SESSION['trial'];
    
    $query = "SELECT category_order FROM participant_status where participant_id=".$_SESSION['pid'];
    $result = getSelect($query);
    $order = explode(":", $result['category_order']);
    $current_category = $order[$_SESSION['trial']-1];
    //echo $result['category_order']."_".$current_category;
    
    if ($current_category == '1') {
        $_SESSION['current_category'] = 'drink';
        include 'screen1_drink.php';
    } else if ($current_category == '2') {
        $_SESSION['current_category'] = 'bottle';
        include 'screen1_bottle.php';
    } else if ($current_category == '3') {
        $_SESSION['current_category'] = 'cereal';
        include 'screen1_cereal.php';
    } else if ($current_category == '4') {
        $_SESSION['current_category'] = 'snack';
        include 'screen1_snacks.php';
    } else if ($current_category == '5') {
        $_SESSION['current_category'] = 'spice';
        include 'screen1_spices.php';
    }
?>