<!-- Displays images from test2 folder -->

<?php 
	
 // Displays the images
$files = glob("test2/*.jpg");
for ($i=0; $i<count($files); $i++)
{
    $num = $files[$i];
    echo '<img src="'.$num.'" alt="random image" width="150" height="150">'."&nbsp;&nbsp;";
}

?>