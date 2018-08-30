<?php
	$GLOBALS['conn']->close();
?>


<script>
	console.log('<?php echo $_SESSION['pid']."_".$_SESSION['pcode']."_".$_SESSION['trial']?>');
</script>

        <pre id='log'></pre>
		<br><br><br> <!-- space at the bottom -->

		</div>
	</body>
</html>