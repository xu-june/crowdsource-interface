<?php
function printProgressBar($step){
?>
	<div class="progress mb-3">
	  <div class="progress-bar" role="progressbar" style="width: <?= ($step/18) * 100 ?>%;" aria-valuenow="<?=$step?>" aria-valuemin="1" aria-valuemax="18"><?=$step?> / 18</div>
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
