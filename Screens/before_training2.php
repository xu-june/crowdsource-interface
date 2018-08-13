<?php
	include 'header.php';
?>

<!DOCTYPE html>

<html>
<head>
	<title>Training 2</title>
	<?php printMetaInfo(); ?>
</head>
<body>
	<div class="mt-3 mb-3 mr-3 ml-3">
		<?php printProgressBar(13); ?>
    
		<h3>You told us what you would do differently, now show us!</h3>
		<p>On the next screen, take 30 more pictures of the requested object.</p>

		<div align="right">
			<button type="button" class="btn btn-default" onclick="window.location.href='training2.php'">Next ></button>
		</div>
	</div>
</body>
</html>