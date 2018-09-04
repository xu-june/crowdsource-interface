<?php
    session_start();
    include 'connectDB.php';
    include 'header.php';
	savePageLog($_SESSION['pid'], basename($_SERVER['PHP_SELF']));
    
    training_start('train1');
    
    $date = date("Y-m-d H:i:s");
    $time = round(microtime(true) * 1000);
    $sql = "INSERT INTO action_logs "
            ."(`participant_id`, `trial`, `event`, `time`, `date`) VALUES ("
            .$_SESSION['pid'].",".$_SESSION['trial'].", 'training1_started', '".$time."', '".$date."');";
    execSQL($sql);
        
    printProgressBar(10); 
?>

<h4>Questions </h4><br>        
The training process takes about 2 minutes. Please answer the question below while you are waiting.

<div id="myProgress" class="mt-3 mb-3">
  <div id="myBar"></div>
</div>

<form id='feedForm' action="" method="post">
  <!-- text form -->
  <div class="form-group">
    <label for="q1"><strong>What do you think is important when you take images for training object recognizer? How did it affect you in the previous step of collecting images?</strong></label>
    <textarea class="form-control" required="true" id="q1" name='q1' rows="5"></textarea>
  </div>
  
  <div align="right">
    <button type="button" id='next_button' class="btn btn-primary" style='display:none;' onclick="submit_trq(1);">Next ></button>
    <button type="submit" id='submit_button' class="btn btn-primary" style='display:none;'>Next ></button>
  </div>
</form>

<script>
    function progress() {
        var elem = document.getElementById("myBar"); 
        var width = 0;
        var id = setInterval(frame, 1200);
        function frame() {
            if (width >= 100) {
                clearInterval(id);
                $("#next_button").show();
                $('html, body').animate({
                    scrollTop: $("#next_button").offset().top
                }, 500);
            } else {
                width++; 
                elem.style.width = width + '%'; 
                elem.innerHTML = width * 1 + '%';
            }
        }
    }
    progress();
</script>



