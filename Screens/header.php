<?php
    function getObjectIndex($phase, $cnt){
        if (strpos($phase, 'test') === 0) {
            $order = $_SESSION['order'][$phase];
            return $order[min($cnt, 14)];
        } else if (strpos($phase, 'train') === 0) {
            $order = $_SESSION['order'][$phase];
            return $order[min(floor($cnt/30), 2)];
        } else if (strpos($phase, 'subset') === 0){
            $subset_for = 'train1';
            if (strpos($phase, 'train1') === false)
                $subset_for = 'train2';
            
            $order = $_SESSION['order'][$subset_for];
            return $order[$cnt];
        } else {
            return 0;
        }
    }    
    function removeSpecialChars($string) {
       return preg_replace('/[^A-Za-z0-9 ]/', '', $string); // Removes special chars.
    }
    function training_start($p) {
		// trigger the training for now
		require(dirname(__FILE__).'/../TOR/rest_client.php');
		// send the training images to the server
		$puuid = 'p' . $_SESSION['pid'];
		$trial = 't' . $_SESSION['trial'];
		// TODO: we need to trigger a training only once
		init_vars();
		prepare_upload($puuid, $trial, $p);
    }
    function request_training($p) {
		// trigger the training for now
		require(dirname(__FILE__).'/../TOR/rest_client.php');
		// send the training images to the server
		$puuid = 'p' . $_SESSION['pid'];
		$trial = 't' . $_SESSION['trial'];
        
		init_vars();
        $zip_file = compress_images($puuid, $trial, $p);
        return upload_and_train($puuid, $trial, $p, $zip_file);
    }
    function check_waiting($p) {
		// trigger the training for now
		require(dirname(__FILE__).'/../TOR/rest_client.php');
		// send the training images to the server
		$puuid = 'p' . $_SESSION['pid'];
		$trial = 't' . $_SESSION['trial'];
        
		init_vars();
        return check_for_training($puuid, $p);
    }       
    function replaceSpecial($str){
        $res = $str;
        $res = str_replace("=","-", $res);
        $res = str_replace("~","--xxa--", $res);
        $res = str_replace("`","--xxb--", $res);
        $res = str_replace("!","--xxc--", $res);
        $res = str_replace("@","--xxd--", $res);
        $res = str_replace("#","--xxe--", $res);
        $res = str_replace("$","--xxf--", $res);
        $res = str_replace("%","--xxg--", $res);
        $res = str_replace("^","--xxh--", $res);
        $res = str_replace("&","--xxi--", $res);
        $res = str_replace("*","--xxj--", $res);
        $res = str_replace("(","--xxk--", $res);
        $res = str_replace(")","--xxl--", $res);
        $res = str_replace("+","--xxn--", $res);
        $res = str_replace("{","--xxp--", $res);
        $res = str_replace("}","--xxq--", $res);
        $res = str_replace("[","--xxr--", $res);
        $res = str_replace("]","--xxs--", $res);
        $res = str_replace("|","--xxt--", $res);
        $res = str_replace("\\","--xxu--", $res);
        $res = str_replace("'","--xxv--", $res);
        $res = str_replace("\"","--xxw--", $res);
        $res = str_replace(":","--xxx--", $res);
        $res = str_replace(";","--xxy--", $res);
        $res = str_replace("/","--xxz--", $res);
        $res = str_replace("?","--xxma--", $res);
        $res = str_replace("<","--xxmb--", $res);
        $res = str_replace(">","--xxmc--", $res);
        $res = str_replace(".","--xxmd--", $res);
        $res = str_replace(",","--xxme--", $res);
        $res = str_replace(" ","--xxmf--", $res);
        return $res;
    }
    function restoreSpecial($str){
        $res = $str;
        $res = str_replace("--xxa--", "~",$res);
        $res = str_replace("--xxb--", "`",$res);
        $res = str_replace("--xxc--", "!",$res);
        $res = str_replace("--xxd--", "@",$res);
        $res = str_replace("--xxe--", "#",$res);
        $res = str_replace("--xxf--", "$",$res);
        $res = str_replace("--xxg--", "%",$res);
        $res = str_replace("--xxh--", "^",$res);
        $res = str_replace("--xxi--", "&",$res);
        $res = str_replace("--xxj--", "*",$res);
        $res = str_replace("--xxk--", "(",$res);
        $res = str_replace("--xxl--", ")",$res);
        $res = str_replace("--xxn--", "+",$res);
        $res = str_replace("--xxo--", "=",$res);
        $res = str_replace("--xxp--", "{",$res);
        $res = str_replace("--xxq--", "}",$res);
        $res = str_replace("--xxr--", "[",$res);
        $res = str_replace("--xxs--", "]",$res);
        $res = str_replace("--xxt--", "|",$res);
        $res = str_replace("--xxu--", "\\",$res);
        $res = str_replace("--xxv--", "'",$res);
        $res = str_replace("--xxw--", "\"",$res);
        $res = str_replace("--xxx--", ":",$res);
        $res = str_replace("--xxy--", ";",$res);
        $res = str_replace("--xxz--", "/",$res);
        $res = str_replace("--xxma--", "?",$res);
        $res = str_replace("--xxmb--", "<",$res);
        $res = str_replace("--xxmc--", ">",$res);
        $res = str_replace("--xxmd--", ".",$res);
        $res = str_replace("--xxme--", ",",$res);
        $res = str_replace("--xxmf--", " ",$res);
        return $res;
    }
?>
<?php
function printProgressBar($step){
?>
	<div class="progress mb-3" id='progress_bar'>
	  <div class="progress-bar" role="progressbar" style="width: <?= ($step/39) * 100 ?>%;" aria-valuenow="<?=$step?>" aria-valuemin="1" aria-valuemax="18"><?=$step?> / 39</div>
	</div>
<?php
}
?>
<?php
function printMetaInfo() {
?>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/custom_style.css">
    <script src="./js/jquery-3.3.1.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/js-functions.js"></script>
    
    <script>
		var refreshTime = 600000; // every 10 minutes in milliseconds
		window.setInterval( function() {
			$.ajax({
				cache: false,
				type: "GET",
				url: "refreshSession.php",
				success: function(data) {
				}
			});
		}, refreshTime );
	</script>
<?php
}
?>

