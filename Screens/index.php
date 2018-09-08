<?php
	include 'header.php';
	session_destroy();
?>

<!doctype html>
<html lang="en">
  <head>
  <?php printMetaInfo(); ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bowser/1.9.3/bowser.min.js"></script>
  <title>
    	Welcome!
    </title>
  </head>
  
	<body>
		<div class="mt-3 mb-3 mr-3 ml-3">
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
			<div id="main">
			<p> In this study, you will be asked to take photos of everyday products to teach any phone to automatically recognize them. </p>
            <p>The study takes about 20 minutes.</p>
		
            
            <!--
			<p>Please proceed to the study <strong>only if</strong> you are seeing this screen in a mobile device with a built-in camera. Otherwise copy this link to your mobile device.</p><br>
		
			<div class="alert alert-info">
				<p><em>If you are continuing this study from the last session, please enter the code we shared with you here. 
                <p class="text-danger">You will not be compensated if you participate for more than once without your code.</p> 
                You don't need to enter a code if this is your first participation in this study.</em></p>
				<form id='codeForm' action='background.php' method='post'>
					<div class="form-group row">
						<label for="code" class="col-2 col-form-label">Code:</label>
						<div class="col-10">
							<input type="text" class="form-control" id="code" name="code" placeholder="Enter code">
						</div>
					</div>
				</form>
			</div>
			
			<div align='center'>
				<button type="button" id="nextButton" class="btn btn-primary" onclick="document.getElementById('codeForm').submit();">Start</button>
			</div>
            -->
            <div align='center'>
				<button type="button" id="nextButton" class="btn btn-primary" onclick="window.location.href='background.php';">Start</button>
			</div>
        	</div>

	   </div> 
	   
       <div id='test'></div>
       
		<script>
			window.mobileAndTabletcheck = function() {
			  var check = false;
			  (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
			  return check;
			};
			if (!window.mobileAndTabletcheck()) {
				//alert("Please open this link in a mobile device to continue with the study.");
				$("#main").empty();
				$("#main").append("<p class='bg-warning text-dark' align='center'>Please open this link in a mobile device to continue with the study.</p>");
				$("#nextButton").hide();
			} else {
                var browser = bowser.name.toLowerCase();
                var os = bowser.osname.toLowerCase();
                var osversion = bowser.osversion;
                var browserversion = bowser.version;
                
                if (os.includes("android")) {
                	if (browserversion < 28.0) {
                		$("#main").empty();
                        $("#main").append("<p class='bg-warning text-dark' align='center'>This website works on Chrome version 28.0 or higher. The version of your Chrome is "+ browserversion + "."+
                        				" Please update Chrome to participate this study. </p>");
                        $("#nextButton").hide();
                	}
                	
                    if (browser.includes("chrome")) {
                    } else {
                        $("#main").empty();
                        $("#main").append("<p class='bg-warning text-dark' align='center'>Please use Chrome if you are Android device user.</p>");
                        $("#nextButton").hide();
                    }
                } else if (os.includes("ios")){
                	if (osversion < 11.0) {
                		$("#main").empty();
                        $("#main").append("<p class='bg-warning text-dark' align='center'>This website works on iOS version 11.0 or higher. The version of your iOS is "+ osversion + "."+
                        				" Please update iOS to participate this study. </p>");
                        $("#nextButton").hide();
                	}
                	
                    if (browser.includes("safari")) {
                    } else {
                        $("#main").empty();
                        $("#main").append("<p class='bg-warning text-dark' align='center'>Please use Safari if you are iOS device user.</p>");
                        $("#nextButton").hide();
                    }
                }
                
                /*
                var browser_name = '';
                browser_name = "You are using " + bowser.name + " v" + bowser.version + " on " + bowser.osname + " " + bowser.osversion;
                $("#test").append("<br>"+browser_name);
                $("#test").append("<br>"+screen.height + "_" + screen.width + "_" + $(window).height() + "_" + $(window).width() + "_" + $(document).height() + "_" + $(document).width());
                $("#test").append("<br>"+window.navigator.userAgent);
                */
            }
		</script>
  </body>
</html>