<?php
	include 'header.php';
?>

<!DOCTYPE html>
<html>
<head>
	<title>Feedback</title>
	<?php printMetaInfo(); ?>

	<script>
		function countChar1(val) {
	        var length = val.value.length;
	        if (length >= 800) {
	          val.value = val.value.substring(0, 800);
	        } else {
	          $('#charCount1').text(800 - length);
	        }
	      };
	      function countChar2(val) {
	        var length = val.value.length;
	        if (length >= 800) {
	          val.value = val.value.substring(0, 800);
	        } else {
	          $('#charCount2').text(800 - length);
	        }
	      };
	      
    </script>
</head>
<body>
	<div class="mt-3 mb-3 mr-3 ml-3">
		<?php printProgressBar(7); ?>
    
		<form name="feedback" action="before_training2.php" method="post">
			<h3>Compare the result to your expectations!</h3>
			<p>Did you expect to get this result? Or did you expect the accuracy to be higher/lower?</p>
			<p>Please tell us what you expected and why, as well as your result and why you think you got this result.</p>
			<textarea name="feedback1" required="true" class="form-control" id="feedback1" rows="3" onkeyup="countChar1(this)" placeholder="Enter feedback here"></textarea>
			<div class="chcount"><small><span id="charCount1">800</span>/800 characters left</small></div>
			<br>

			<p>If you were to retrain the system, what would you do differently? Think in terms of photo style, angle, lighting, etc.</p>
			<textarea name="feedback2" id="feedback2" required="true" class="form-control" rows="3" onkeyup="countChar2(this)" placeholder="Enter feedback here"></textarea>
			<div class="chcount"><small><span id="charCount2">800</span>/800 characters left</small></div>


			<button type="submit" class="btn btn-default">Next ></button>
		</form>
		
	</div>
</body>
</html>