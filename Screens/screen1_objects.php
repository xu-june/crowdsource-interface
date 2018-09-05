<?php
	session_start();
	include 'connectDB.php';
	include 'header.php';
	savePageLog($_SESSION['pid'], basename($_SERVER['PHP_SELF']));
    
    /* return Operating System */
    function operating_system_detection(){
        //Detect special conditions devices
        $iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
        $iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
        $iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
        $Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
        $webOS   = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");

        //do something with this information
        if( $iPod ){
            //browser reported as an iPhone/iPod touch -- do something here
            return 'iPod';
        } else if ($iPhone) {
            return 'iPhone';
        } else if($iPad){
            return 'iPad';
        }else if($Android){
            return 'Android';
        }else if($webOS){
            return 'webOS';
        }
    }

    if (!empty($_POST['bq4'])) {
        $q4 = $_POST["bq4"];
        $q5 = $_POST["bq5"];
        $q6 = $_POST["bq6"];
        $q7 = $_POST["bq7"];
        $sql = "UPDATE participant_info set `bq4`='".$q4."', `bq5`='".$q5."', `bq6`='".$q6."', `bq7`='".$q7."' WHERE `participant_id`=".$_SESSION['pid'].";";
        execSQL($sql);
    }
    //echo $_SESSION['pid']."-".$_SESSION['pcode']."-".$_SESSION['trial'];
        
    $date = date("Y-m-d H:i:s");
    $time = round(microtime(true) * 1000);
    // add variables
    $sql = "DELETE from variables where `participant_id`=".$_SESSION['pid']." and `trial`=".$_SESSION['trial'].";";
    execSQL($sql);
    
    $sql = "INSERT INTO variables (`participant_id`, `trial`, `phase`, `time`, `date`) "
            ."VALUES ( ".$_SESSION['pid'].",".$_SESSION['trial'].", 'test0', '".$time."', '".$date."');";
    execSQL($sql);
    
    //delete existing feedback info
    $sql = "DELETE from feedback WHERE `participant_id`=".$_SESSION['pid']." and `trial`=".$_SESSION['trial'].";";
    execSQL($sql);
    
    // insert feedback info
    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    $browser = 'Something else';
    if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE)
        $browser = 'Internet explorer';
    elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) //For Supporting IE 11
        $browser = 'Internet explorer';
    elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE)
        $browser = 'Mozilla Firefox';
     elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE)
        $browser = 'Google Chrome';
     elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== FALSE)
        $browser = "Opera Mini";
     elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== FALSE)
        $browser = "Opera";
     elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE)
        $browser = "Safari";
    $sql = "INSERT INTO feedback (`participant_id`, `trial`, `language`, `browser`, `os`) VALUES(".$_SESSION['pid'].", ".$_SESSION['trial'].", '".$lang."', '".$browser."', '".operating_system_detection()."');";
    execSQL($sql);
    
    /*
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
    */
    //$_SESSION['current_category'] = 'spice';
    //include 'screen1_spices.php';
    
	$sql = "delete from Objects where `participant_id`=".$_SESSION['pid']." and `trial`=".$_SESSION['trial'].";";
	execSQL($sql);
	
    $sql = "SELECT category FROM Objects where participant_id=".$_SESSION['pid'].";";
	$result = getConn()->query($sql);

	$categories = array("bottle", "cereal", "drink", "snack", "spice");
	$categories_done = array();
	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			array_push($categories_done, $row['category']);
		}
	}
?>


<!doctype html>
<html lang="en">
  <head>
	<script src="./js/popper.js"></script>
  <?php printMetaInfo(); ?>
  	
  <title>
    	Data Collection Condition
    </title>
    <script>
    	function update_category(category){
	    	$('#category').text(category);
    		$('#example').load('screen1_' + category.toLowerCase()+'.php');
	    	$('#nextButton').show();
    	}
    </script>
  </head>
  
	<body>
		<div class="mt-3 mb-3 mr-3 ml-3">
			<?php printProgressBar(4); ?>
		
			<h4>Data Collection Condition</h4>
			
			<p>
			Select a category of object that you have three instances of. 
			
			<div class="btn-group">
			  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id='category' style="width:10wv;">
				Category
			  </button>
			  <div class="dropdown-menu">
			  	<?php
			  		for ($i = 0; $i < 5; $i++) {
			  			if (!in_array($categories[$i], $categories_done)) {
			  				$cat = ucfirst($categories[$i]);
							echo "<a class=\"dropdown-item\" onclick=\"update_category('".$cat."');\">".$cat."</a>";
						}
					} 
			  	?>
			  </div>
			</div>
			</p>
			
			<div id='example'></div>
<?php
	include 'footer.php';
?>