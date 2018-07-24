<!-- Code from https://www.formget.com/php-checkbox/ -->
	<?php
	// Configuring errors
	ini_set('display_errors',1);
	error_reporting(E_ALL);
	var_dump($_POST); 

	$scr5_subselect20 = array();
	if(isset($_POST['selections']) && is_array($_POST['selections']))
	{ //to run PHP script on submit
		if(!empty($_POST['selections']))
		{
			// Copy each file name into $scr5_subselect20
			foreach($_POST['selections'] as $selected)
			{
				$scr5_subselect20[] = $selected;
			}
			// Display name of each file selected
			foreach($scr5_subselect20 as $image)
			{
				echo $image."</br>";
			}
		}
		// if (empty($scr5_subselect20)) 
		// {
		// 	echo "array is empty";
		// }
	}
	?>