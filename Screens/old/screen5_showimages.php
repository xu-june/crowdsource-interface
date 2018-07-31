<!-- Displays images from train1 folder -->

<?php 
	
 // Displays the images
$files = glob("train1/*.jpg");
for ($i=0; $i<count($files); $i++)
{
    $num = $files[$i];
    echo '<img src="'.$num.'" width="150" height="150">'."&nbsp;&nbsp;";
}

?>