<?php
	session_start();
	include 'connectDB.php';
	include 'header.php';
	savePageLog($_SESSION['pid'], basename($_SERVER['PHP_SELF']));
    
    $_SESSION['current_category'] = $_POST['category'];
    
    $examples = array();
    if ($_SESSION['current_category']=='drink'){
        $examples = array('Coca cola', 'Pepsi', 'Sprite');
    } else if ($_SESSION['current_category']=='bottle'){
        $examples = array('Coca cola', 'Dasani', 'Sprite');
    } else if ($_SESSION['current_category']=='cereal'){
        $examples = array('Lucky charms', 'Cheerios', 'Frosted flakes');
    } else if ($_SESSION['current_category']=='snack'){
        $examples = array('Potato chips', 'Fritos', 'Pretzels');
    } else if ($_SESSION['current_category']=='spice'){
        $examples = array('Cinnamon', 'Nutmeg', 'Chili powder');
    }
?>

<!doctype html>
<html lang="en">
  <head>
  <?php printMetaInfo(); ?>
  <title>
    	Name your objects!
    </title>
  </head>
  
	<body>
		<div class="mt-3 mb-3 mr-3 ml-3">
  			<?php printProgressBar(5); ?>
		<script>
			function submit() {
				document.getElementById("submitbtn").click();
			}
			function submit_objects() {
				document.getElementById("submitbtn").click();
			} 
            function isValid(str){
                return !/[~`!#$%\^&*+=\-\[\]\\';,/{}|\\":<>\?]/g.test(str);
            }
            function submit_form() {
                var obj1 = $("#obj1").val();
                var obj2 = $("#obj2").val();
                var obj3 = $("#obj3").val();
                
                /*
                if (!isValid(obj1) || !isValid(obj2) || !isValid(obj3)) {
                    alert('No special characters in the object name is allowed.');
                    return;
                }
                */
                if (replaceSpecial(obj1.trim()) == replaceSpecial(obj2.trim()) || replaceSpecial(obj1.trim()) == replaceSpecial(obj3.trim()) 
                    || replaceSpecial(obj2.trim()) == replaceSpecial(obj3.trim())) {
                    alert('Objects should have different names.');
                    return;
                }
                
                if (obj1.trim() == '' || obj2.trim() == '' || obj3.trim() == '') {
                    alert('A name should not be empty');
                }
                
                document.getElementById("objectForm").submit();
            }
		</script>
		<h4>Name your objects!</h4>
		<p>Assign your objects to Object 1, Object 2, and Object 3.</p>


		<form name="form" action="before_test0.php" method="post" id='objectForm'>
			<table cellspacing="10">
				<tr>
					<td width="20%">
						<img src="images/<?=$_SESSION['current_category']?>_green.png" width="100%">
					</td>
					<td>
						<div><label for="obj1">Object 1: </label></div>
						<input type="text" required="true" name="obj1" required="true" class="form-control" id="obj1" placeholder="Name of object 1 (e.g., <?=$examples[0]?>)">
                        <br>
					</td>
				</tr>
			</table>
			<table>
				<tr>
					<td width="20%">
						<img src="images/<?=$_SESSION['current_category']?>_orange.png" width="100%">
					</td>
					<td>
						<div><label for="obj2">Object 2: </label></div>
						<input type="text" required="true" name="obj2" required="true" class="form-control" id="obj2" placeholder="Name of object 2 (e.g., <?=$examples[1]?>)">
                        <br>
					</td>
				</tr>
			</table>
			<table>
				<tr>
					<td width="20%">
						<img src="images/<?=$_SESSION['current_category']?>_blue.png" width="100%">
					</td>
					<td>
						<div><label for="obj3">Object 3: </label></div>
						<input type="text" required="true" name="obj3" required="true" class="form-control" id="obj3" placeholder="Name of object 3 (e.g., <?=$examples[2]?>)">
                        <br>
					</td>
				</tr>
			</table>
			
			<div align='right'>
				<input type="button"  class="btn btn-primary" value="Next" id="submitbtn" onclick='submit_form();'>
			</div>
			
			<!-- php code is moved to before_test0.php -->
			
		</form>
        
        <script>
            var screen_size = screen.height + "_" + screen.width + "_" + $(window).height() + "_" + $(window).width() + "_" + $(document).height() + "_" + $(document).width();
            $.ajax({
              type: "POST",
              url: "requestHandler.php",
              data: { 
                 type: 'submit_screen_size',
                 screen_size: screen_size
              },
              success: function (data) {
                console.log('success');
                console.log(data.trim());
              },
              error: function () { console.log('fail'); }
            }).done(function(o) {
              console.log('done'); 
            });
        </script>
<?php
	include 'footer.php';
?>