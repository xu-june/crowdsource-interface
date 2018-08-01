<?php
function printProgressBar($step){
?>
	<div class="progress mb-3">
	  <div class="progress-bar" role="progressbar" style="width: <?=$step?>0%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><?=$step?> / 10</div>
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
    <script src="./js/jquery-3.3.1.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/js-functions.js"></script>
<?php
}
?>