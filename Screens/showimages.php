<!-- Displays images -->

<?php 

session_start();
$phase = $_SESSION['phase'];
$object = $_SESSION['currObj'];

// echo "<p></p>";
// echo $folder . " is the folder";
// echo "<p></p>";

 // Displays the images
$files = glob("images/12345/".$phase."/".$object."/*.png");
$numFiles = count($files);

echo $numFiles ." images taken";
echo "<p></p>";

for ($i=0; $i<$numFiles; $i++)
{
    $num = $files[$i];
    echo '<img src="'.$num.'" width="150" height="150">'."&nbsp;&nbsp;";
}

?>