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
    function replaceSpecial($str){
        $res = $str;
        $res = str_replace("=","-", $res);
        $res = str_replace("~","--a--", $res);
        $res = str_replace("`","--b--", $res);
        $res = str_replace("!","--c--", $res);
        $res = str_replace("@","--d--", $res);
        $res = str_replace("#","--e--", $res);
        $res = str_replace("$","--f--", $res);
        $res = str_replace("%","--g--", $res);
        $res = str_replace("^","--h--", $res);
        $res = str_replace("&","--i--", $res);
        $res = str_replace("*","--j--", $res);
        $res = str_replace("(","--k--", $res);
        $res = str_replace(")","--l--", $res);
        $res = str_replace("+","--n--", $res);
        $res = str_replace("{","--p--", $res);
        $res = str_replace("}","--q--", $res);
        $res = str_replace("[","--r--", $res);
        $res = str_replace("]","--s--", $res);
        $res = str_replace("|","--t--", $res);
        $res = str_replace("\\","--u--", $res);
        $res = str_replace("'","--v--", $res);
        $res = str_replace("\"","--w--", $res);
        $res = str_replace(":","--x--", $res);
        $res = str_replace(";","--y--", $res);
        $res = str_replace("/","--z--", $res);
        $res = str_replace("?","--ma--", $res);
        $res = str_replace("<","--mb--", $res);
        $res = str_replace(">","--mc--", $res);
        $res = str_replace(".","--md--", $res);
        $res = str_replace(",","--me--", $res);
        $res = str_replace(" ","-----", $res);
        return $res;
    }
    function restoreSpecial($str){
        $res = $str;
        $res = str_replace("--a--", "~",$res);
        $res = str_replace("--b--", "`",$res);
        $res = str_replace("--c--", "!",$res);
        $res = str_replace("--d--", "@",$res);
        $res = str_replace("--e--", "#",$res);
        $res = str_replace("--f--", "$",$res);
        $res = str_replace("--g--", "%",$res);
        $res = str_replace("--h--", "^",$res);
        $res = str_replace("--i--", "&",$res);
        $res = str_replace("--j--", "*",$res);
        $res = str_replace("--k--", "(",$res);
        $res = str_replace("--l--", ")",$res);
        $res = str_replace("--n--", "+",$res);
        $res = str_replace("--o--", "=",$res);
        $res = str_replace("--p--", "{",$res);
        $res = str_replace("--q--", "}",$res);
        $res = str_replace("--r--", "[",$res);
        $res = str_replace("--s--", "]",$res);
        $res = str_replace("--t--", "|",$res);
        $res = str_replace("--u--", "\\",$res);
        $res = str_replace("--v--", "'",$res);
        $res = str_replace("--w--", "\"",$res);
        $res = str_replace("--x--", ":",$res);
        $res = str_replace("--y--", ";",$res);
        $res = str_replace("--z--", "/",$res);
        $res = str_replace("--ma--", "?",$res);
        $res = str_replace("--mb--", "<",$res);
        $res = str_replace("--mc--", ">",$res);
        $res = str_replace("--md--", ".",$res);
        $res = str_replace("--me--", ",",$res);
        $res = str_replace("-----", " ",$res);
        return $res;
    }
    
?>
<?php
function printProgressBar($step){
?>
	<div class="progress mb-3">
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

