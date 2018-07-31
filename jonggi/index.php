<?php
	include 'header.php';
	printIndexHeader('Background', 1);
?>
        <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light" style="background:transparent url('images/intro_image.png') no-repeat center top /cover">
              <div class="col-md-2 p-lg-2 mx-auto my-2">
                <h1 class="text-white display-4 font-weight-normal">Welcome</h1>
                <!--
                <p class="lead font-weight-normal">And an even wittier subheading to boot. Jumpstart your marketing efforts with this example based on Apple's marketing pages.</p>
                <a class="btn btn-outline-secondary" href="#">Coming soon</a>
                -->
              </div>
        </div>
        
        <br>
        <p> We are studying the patterns of using a camera for recognizing objects. 
        In this study, You will be asked to take photos of everyday products such as soda cans,
cereal boxes, and spices to teach your phone to automatically recognize them.
        The duration of this study is around 60 minutes. </p>
        
        <div class="text-warning">A mobile device is required to complete the study and it MUST be able to take photos that
you can choose to train the object recognizer</div><br>
        
        <div class="alert alert-info">
            <p><em>If you have completed this study before, please enter your code here. <p class="text-danger">You will not be compensated if you participate again without your code.</p></em></p>
            <form>
                <div class="form-group row">
                    <label for="code" class="col-2 col-form-label">Code:</label>
                    <div class="col-10">
                        <input type="text" class="form-control" id="code" placeholder="Enter code">
                    </div>
                </div>
            </form>
        </div>
        
        <ul class="pagination justify-content-center">
            <li class="page-item"><a class="page-link" href="background.php">Start</a></li>
        </ul>
        <!--<button type="button" class="btn btn-link"> Start </button>-->
        
<?php
	include('footer.php');
?>