<!-- Displays images from train1 folder -->

<?php 

session_start();
$folder = $_SESSION['currObj'];
echo "<p></p>";
echo $folder . " is the folder";
echo "<p></p>";

 // Displays the images
$files = glob("images/12345/train1/".$folder."/*.jpg");
for ($i=0; $i<count($files); $i++)
{
    $num = $files[$i];
    echo '<img src="'.$num.'" width="150" height="150">'."&nbsp;&nbsp;";
}

?>