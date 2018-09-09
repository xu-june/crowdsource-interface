<?php
    session_start();
    include 'connectDB.php';
    include 'header.php';
	savePageLog($_SESSION['pid'], basename($_SERVER['PHP_SELF']));
    
    //training_start('train1');
    
    $date = date("Y-m-d H:i:s");
    $time = round(microtime(true) * 1000);
    $sql = "INSERT INTO action_logs "
            ."(`participant_id`, `trial`, `event`, `time`, `date`) VALUES ("
            .$_SESSION['pid'].",".$_SESSION['trial'].", 'training1_started', '".$time."', '".$date."');";
    execSQL($sql);
        
    printProgressBar(10); 
?>

<h4> State of Training </h4>  
<div id='waiting' align='center' class='mt-3 mb-3'>Wait for another participant to finish training.. <br>(0 participants before you)</div>

<div id="myProgress" class="mt-3 mb-3" style='display:none;'>
  <div id="myBar"></div>
</div>


<h4>Questions </h4>        
<p>The training process takes about 1 minute. Please answer the question below while you are waiting.</p>

<form id='feedForm' action="" method="post">
  <!-- text form -->
  <div class="form-group">
    <label for="q1"><strong>What did you think was important to consider when training the object recognizer?</strong></label>
    <textarea class="form-control" required="true" id="q1" name='q1' rows="3"></textarea>
  </div>
  
  <!-- text form -->
  <div class="form-group">
      <fieldset class="form-group">
          <legend class="col-form-label pt-0"><strong>How certain are you that the object recognizer is robust and will work anywhere, anytime, for anyone? </strong></legend>
          <div>
            <table width="100%">
            <tr>
                <td>
                    <div class="form-check mb-1">
                      <input class="form-check-input" required="true" type="radio" name="q2" id="vu" value="1" >
                      <label class="form-check-label" for="vu">
                        <p class='radio_font'>Very uncertain</p>
                      </label>
                    </div>
                </td>
                <td>
                    <div class="form-check mb-1">
                      <input class="form-check-input" required="true" type="radio" name="q2" id="u" value="2">
                      <label class="form-check-label" for="u">
                            <p class='radio_font'>Uncetain</p>
                      </label>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-check">
                      <input class="form-check-input" required="true" type="radio" name="q2" id="c" value="3">
                      <label class="form-check-label" for="c">
                        <p class='radio_font'>Certain</p>
                      </label>
                    </div>
                </td>
                <td>
                    <div class="form-check">
                      <input class="form-check-input" required="true" type="radio" name="q2" id="vc" value="4">
                      <label class="form-check-label" for="vc">
                        <p class='radio_font'>Very certain</p>
                      </label>
                    </div>
                </td>
            </tr>
            </table>
            
          </div>          
      </fieldset>
    Why?
    <textarea class="form-control" required="true" id="q3" name='q3' rows="3"></textarea>
  </div>
  
  <div align="right">
    <button type="button" id='next_button' class="btn btn-primary" style='display:none;' onclick="submit_trq(1);">Next</button>
    <button type="submit" id='submit_button' class="btn btn-primary" style='display:none;'>Next</button>
  </div>
</form>
<script>
    request_training('train1');
</script>



