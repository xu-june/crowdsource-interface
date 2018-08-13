<?php
	include 'header.php';
?>

<!DOCTYPE html>
<html>
<head>
	<title>Draft of Feedback Screen</title>
	<?php printMetaInfo(); ?>

	<script>
		function countChar(val) {
	        var length = val.value.length;
	        if (length >= 800) {
	          val.value = val.value.substring(0, 800);
	        } else {
	          $('#charCount').text(800 - length);
	        }
	      };
    </script>
</head>
<body>
	<div class="mt-3 mb-3 mr-3 ml-3">
		<?php printProgressBar(9); ?>
    
		<h3>Compare the result to your expectations!</h3>
		<p>Did you expect to get this result? Or did you expect the accuracy to be higher/lower?</p>
		<p>Please tell us what you expected and why, as well as your result and why you think you got this result.</p>

		<form name="feedback" action="post_questions.php" method="post">
			<textarea name="feedback1" required="true" class="form-control" id="feedback1" rows="3" onkeyup="countChar(this)" placeholder="Enter feedback here"></textarea>
			<div class="chcount"><small><span id="charCount">800</span>/800 characters left</small></div>
			<br>

			<button type="submit" class="btn btn-default">Next ></button>
		</form>
	</div>
</body>
</html>