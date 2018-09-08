function submit_objects() {
	document.getElementById("submitbtn").click();
}
      
function start_overlay_on() {
	document.getElementById("start_overlay").style.display = "block";
	document.getElementById("preview").style.display = "none";
    context.clearRect(0, 0, canvas.width, canvas.height);
}

function start_overlay_off() {
	document.getElementById("start_overlay").style.display = "none";
	document.getElementById("preview").style.display = "block";
}

function end_overlay_on() {
    subset_for = phase;
	document.getElementById("end_overlay").style.display = "block";
	document.getElementById("preview").style.display = "none";
}

function end_overlay_off() {
	document.getElementById("end_overlay").style.display = "none";
	document.getElementById("preview").style.display = "block";
}
function replaceSpecial(str){
    var res = str;
    res = res.replace(/=/g, "-");
    res = res.replace(/~/g, "--xxa--");
    res = res.replace(/`/g, "--xxb--");
    res = res.replace(/\!/g, "--xxc--");
    res = res.replace(/@/g, "--xxd--");
    res = res.replace(/#/g, "--xxe--");
    res = res.replace(/\$/g, "--xxf--");
    res = res.replace(/%/g, "--xxg--");
    res = res.replace(/\^/g, "--xxh--");
    res = res.replace(/&/g, "--xxi--");
    res = res.replace(/\*/g, "--xxj--");
    res = res.replace(/\(/g, "--xxk--");
    res = res.replace(/\)/g, "--xxl--");
    res = res.replace(/\+/g, "--xxn--");
    res = res.replace(/{/g, "--xxp--");
    res = res.replace(/}/g, "--xxq--");
    res = res.replace(/\[/g, "--xxr--");
    res = res.replace(/\]/g, "--xxs--");
    res = res.replace(/\|/g, "--xxt--");
    res = res.replace(/\\/g, "--xxu--");
    res = res.replace(/'/g, "--xxv--");
    res = res.replace(/"/g, "--xxw--");
    res = res.replace(/:/g, "--xxx--");
    res = res.replace(/;/g, "--xxy--");
    res = res.replace(/\//g, "--xxz--");
    res = res.replace(/\?/g, "--xxma--");
    res = res.replace(/</g, "--xxmb--");
    res = res.replace(/>/g, "--xxmc--");
    res = res.replace(/\./g, "--xxmd--");
    res = res.replace(/,/g, "--xxme--");
    res = res.replace(/ /g, "--xxmf--");
    //alert(res);
    return res;
}
function restoreSpecial(str){
    var res = str;
    res = res.replace(/--xxa--/g, "~");
    res = res.replace(/--xxb--/g, "`");
    res = res.replace(/--xxc--/g, "!");
    res = res.replace(/--xxd--/g, "@");
    res = res.replace(/--xxe--/g, "#");
    res = res.replace(/--xxf--/g, "$");
    res = res.replace(/--xxg--/g, "%");
    res = res.replace(/--xxh--/g, "^");
    res = res.replace(/--xxi--/g, "&");
    res = res.replace(/--xxj--/g, "*");
    res = res.replace(/--xxk--/g, "(");
    res = res.replace(/--xxl--/g, ")");
    res = res.replace(/--xxn--/g, "+");
    res = res.replace(/--xxp--/g, "{");
    res = res.replace(/--xxq--/g, "}");
    res = res.replace(/--xxr--/g, "[");
    res = res.replace(/--xxs--/g, "]");
    res = res.replace(/--xxt--/g, "|");
    res = res.replace(/--xxu--/g, "\\");
    res = res.replace(/--xxv--/g, "'");
    res = res.replace(/--xxw--/g, "\"");
    res = res.replace(/--xxx--/g, ":");
    res = res.replace(/--xxy--/g, ";");
    res = res.replace(/--xxz--/g, "\/");
    res = res.replace(/--xxma--/g, "?");
    res = res.replace(/--xxmb--/g, "<");
    res = res.replace(/--xxmc--/g, ">");
    res = res.replace(/--xxmd--/g, ".");
    res = res.replace(/--xxme--/g, ",");
    res = res.replace(/--xxmf--/g, " ");
    return res;
}
function restoreSpecial_label(str){
    var res = str;
    res = res.replace(/ xxa/g, "~");
    res = res.replace(/ xxb/g, "`");
    res = res.replace(/ xxc/g, "!");
    res = res.replace(/ xxd/g, "@");
    res = res.replace(/ xxe/g, "#");
    res = res.replace(/ xxf/g, "$");
    res = res.replace(/ xxg/g, "%");
    res = res.replace(/ xxh/g, "^");
    res = res.replace(/ xxi/g, "&");
    res = res.replace(/ xxj/g, "*");
    res = res.replace(/ xxk/g, "(");
    res = res.replace(/ xxl/g, ")");
    res = res.replace(/ xxn/g, "+");
    res = res.replace(/ xxp/g, "{");
    res = res.replace(/ xxq/g, "}");
    res = res.replace(/ xxr/g, "[");
    res = res.replace(/ xxs/g, "]");
    res = res.replace(/ xxt/g, "|");
    res = res.replace(/ xxu/g, "\\");
    res = res.replace(/ xxv/g, "'");
    res = res.replace(/ xxw/g, "\"");
    res = res.replace(/ xxx/g, ":");
    res = res.replace(/ xxy/g, ";");
    res = res.replace(/ xxz/g, "\/");
    res = res.replace(/ xxma/g, "?");
    res = res.replace(/ xxmb/g, "<");
    res = res.replace(/ xxmc/g, ">");
    res = res.replace(/ xxmd/g, ".");
    res = res.replace(/ xxme/g, ",");
    res = res.replace(/ xxmf/g, "");
    return res;
}	
function leaveCheck(){
	if (confirm('Are you sure you want to leave this page?')){
		return true;
	} else {
		return false;
	}
}

function captureImage() {
    if (!clickable) return;
    
    clickable = false;
    if (phase.startsWith("test")) {
        var elem = document.getElementById("label"); 
        elem.innerHTML = "Recognition is going on...";
    	$('#prediction').show();
        videoElement.pause();
    }
    
    show_prev_image();
	var img = document.createElement("img");
	img.src = offScreenCanvas.toDataURL();
    
    $.ajax({
	  type: "POST",
	  url: "upload.php",
	  data: { 
		 imgBase64: img.src,
         phase: phase,
         truth: obj_name
	  },
	  success: function (data) {
          //alert('success!');
		console.log('success');
        console.log(data.trim());
        
        //info = parse_variables(data.trim());
        var info = data.trim().split("=");
        phase = info[0];
        upload_cnt_obj1 = parseInt(info[1]);
        upload_cnt_obj2 = parseInt(info[2]);
        upload_cnt_obj3 = parseInt(info[3]);
        subset_cnt_obj = parseInt(info[4]);
        subset_cnt_num = parseInt(info[5]);
        obj_name = restoreSpecial(info[6]);
        obj_index = parseInt(info[7]);
        label = restoreSpecial_label(info[8]);
        update_interface();
	  },
	  error: function () { 
		console.log('fail'); 
        
		videoElement.play();
		clickable = true;
		showFail();
      }
	}).done(function(o) {
	  //console.log('done'); 
	});
}

function showFail() {
    var elem = document.getElementById("label"); 
    var width = 0;
    var id = setInterval(frame, 1000)
    var cnt = 3;
    function frame() {
        if (cnt <= 1) {
            clearInterval(id);
            
            var upload_cnt = upload_cnt_obj1+upload_cnt_obj2+upload_cnt_obj3;
            clearTest(upload_cnt);
        } else {
            cnt--;
            elem.innerHTML = "Uploading image failed. Try again in "+cnt+" seconds.";
        }
    }
}

function showResult(upload_cnt) {
    var elem = document.getElementById("label"); 
    var width = 0;
    var id = setInterval(frame, 1000)
    var cnt = 3;
    $('#prediction').show();
    function frame() {
        if (cnt <= 1) {
            clearInterval(id);

            var upload_cnt = upload_cnt_obj1+upload_cnt_obj2+upload_cnt_obj3;
            clearTest(upload_cnt);
        } else {
            cnt--;
            elem.innerHTML = "<b>"+label+"</b>";
        }
    }
}

function clearTest(upload_cnt){
    $('#prediction').fadeOut('fast');
    $("#count").text(test_img_num*3-upload_cnt);
    $("#objects").text(obj_name);
    context.clearRect(0, 0, canvas.width, canvas.height);
    
    videoElement.play();
    videoElement.style.borderColor = obj_colors[obj_index-1];
    document.getElementById("objects").style.backgroundColor = obj_colors[obj_index-1];
    clickable = true;
    
    if (upload_cnt == test_img_num*3) {
        $('#taskArea').hide();
        $('#msgArea').show();
        if (phase == 'test0'){
            $('#msgArea').load('before_training1.php');
        } else if (phase=='test1') {
            $('#msgArea').load('feedbackscreen1.php');
        } else if (phase=='test2') {
            $('#msgArea').load('feedbackscreen2.php');
            
        }
    }
}

function update_interface() {
    console.log("update_interface " + phase);
    if (phase.startsWith("test")) {
    	clickable = true;
        var upload_cnt = upload_cnt_obj1+upload_cnt_obj2+upload_cnt_obj3;
        
        canvas.style.display = "none";
        document.getElementById("count_container").align = "left";
        $('#msgArea').empty();
        $('#msgArea').hide();
        $('#taskArea').show();
        
        
        var elem = document.getElementById("label"); 
        elem.innerHTML = "<b>"+label+"</b>";
        
        if (label != ''){
            console.log("label " + label);
            //videoElement.pause();            
            showResult(upload_cnt);
        } else {
            console.log("no label ");
            $("#objects").text(obj_name);
            $("#count").text(test_img_num*3-upload_cnt);
            videoElement.style.borderColor = obj_colors[obj_index-1];
            document.getElementById("objects").style.backgroundColor = obj_colors[obj_index-1];
        }
    } else if (phase.startsWith("train") && phase.length==6) {
        clickable = true;
        var upload_cnt = upload_cnt_obj1+upload_cnt_obj2+upload_cnt_obj3;
        
        canvas.style.display = "block";
        document.getElementById("count_container").align = "right";
        $('#taskArea').show();
        $('#msgArea').empty();
        $('#msgArea').hide();
        
        $("#objects").text(obj_name);
        $("#obj_name1").text(obj_name);
        $("#obj_name2").text(obj_name);
        
        videoElement.style.borderColor = document.getElementById("objects").style.backgroundColor = obj_colors[obj_index-1];
        $("#count").text(training_img_num-upload_cnt % training_img_num);
        
        if (upload_cnt >= training_img_num*3) {
            goToNext(phase+'_question');
        } else if (upload_cnt%training_img_num == 0 ) {
            start_overlay_on();
        }
    } else if (phase.startsWith("subset")) {
        $('#taskArea').hide();
        $('#msgArea').show();
        $('#msgArea').load('subset.php');
    } else {
    	console.log(phase+".php load");
        $('#msgArea').empty();
        $('#msgArea').load(phase+'.php');
        $('#taskArea').hide();
        $('#msgArea').show();
    }
}

function goToNext(p){
    context.clearRect(0, 0, canvas.width, canvas.height);
    var phase_to_send = p;
    if (p == 'train1_question') {
        phase_to_send = 'training1_question';
    } else if (p == 'train2_question') {
        phase_to_send = 'training2_question';
    }
    
    $.ajax({
	  type: "POST",
	  url: "requestHandler.php",
	  data: { 
		 type: 'update_phase',
         phase: phase_to_send
	  },
	  success: function (data) {
		console.log('success');
        console.log(data.trim());
        
        //info = parse_variables(data.trim());
        var info = data.trim().split("=");
        phase = info[0];
        upload_cnt_obj1 = parseInt(info[1]);
        upload_cnt_obj2 = parseInt(info[2]);
        upload_cnt_obj3 = parseInt(info[3]);
        subset_cnt_obj = parseInt(info[4]);
        subset_cnt_num = parseInt(info[5]);
        obj_name = restoreSpecial(info[6]);
        obj_index = parseInt(info[7]);
        label = info[8];
        update_interface();
	  },
	  error: function () { console.log('fail'); }
	}).done(function(o) {
	  //console.log('done'); 
	});
}
	
function getSelected(){
	var result = [];
	var i = 0;
	for (i=0; i<selected.length; i++){
		if (selected[i]){
			result.push(i+1);
		}
	}
	return result.join(":");
}

function submit_selection() {
	$.ajax({
	  type: "POST",
	  url: "requestHandler.php",
	  data: { 
		 type: 'submit_selection',
         limit: limit,
         img_num: img_num,
         subset_cnt_obj: subset_cnt_obj,
         subset_cnt_num: subset_cnt_num,
         subset_for: subset_for,
         selected: getSelected()
	  },
	  success: function (data) {
		console.log('success');
        console.log(data.trim());
        
        var info = data.trim().split("=");
        phase = info[0];
        upload_cnt_obj1 = parseInt(info[1]);
        upload_cnt_obj2 = parseInt(info[2]);
        upload_cnt_obj3 = parseInt(info[3]);
        subset_cnt_obj = parseInt(info[4]);
        subset_cnt_num = parseInt(info[5]);
        obj_name = restoreSpecial(info[6]);
        obj_index = parseInt(info[7]);
        label = info[8];
        update_interface();
	  },
	  error: function () { console.log('fail'); }
	}).done(function(o) {
	  console.log('done'); 
	});
}

function submit_feedback1() {
    var feedbackForm = document.getElementById("feedbackForm");
    if (!feedbackForm.checkValidity()) {
        $("#submit_button").click();
        return;
    }
    subset_for = 'train1';
    
    $.ajax({
	  type: "POST",
	  url: "requestHandler.php",
	  data: { 
		 type: 'submit_feedback1',
         q1: $(".form-check-input:checked").val(),
         q2: $("#q2").val(),
         q3: $("#q3").val()
	  },  
	  success: function (data) {
        console.log(data.trim());
        
        var info = data.trim().split("=");
        phase = info[0];
        upload_cnt_obj1 = parseInt(info[1]);
        upload_cnt_obj2 = parseInt(info[2]);
        upload_cnt_obj3 = parseInt(info[3]);
        subset_cnt_obj = parseInt(info[4]);
        subset_cnt_num = parseInt(info[5]);
        obj_name = restoreSpecial(info[6]);
        obj_index = parseInt(info[7]);
        label = info[8];
        update_interface();
	  },
	  error: function () { console.log('fail'); }
	}).done(function(o) {
	  console.log('done'); 
	});
}

function submit_feedback2() {
    var feedbackForm = document.getElementById("feedbackForm");
    if (!feedbackForm.checkValidity()) {
        $("#submit_button").click();
        return;
    }
    subset_for = 'train2';
    
    $.ajax({
	  type: "POST",
	  url: "requestHandler.php",
	  data: { 
		 type: 'submit_feedback2',
         q1: $(".form-check-input:checked").val(),
         q2: $("#q2").val()
	  },
	  success: function (data) {
        console.log(data.trim());
        
        var info = data.trim().split("=");
        phase = info[0];
        upload_cnt_obj1 = parseInt(info[1]);
        upload_cnt_obj2 = parseInt(info[2]);
        upload_cnt_obj3 = parseInt(info[3]);
        subset_cnt_obj = parseInt(info[4]);
        subset_cnt_num = parseInt(info[5]);
        obj_name = restoreSpecial(info[6]);
        obj_index = parseInt(info[7]);
        label = info[8];
        update_interface();
	  },
	  error: function () { console.log('fail'); }
	}).done(function(o) {
	  console.log('done'); 
	});
}

function submit_trq(index) {
    var feedbackForm = document.getElementById("feedForm");
    if (!feedbackForm.checkValidity()) {
        $("#submit_button").click();
        return;
    }
    
    $.ajax({
	  type: "POST",
	  url: "requestHandler.php",
	  data: { 
		 type: 'submit_trq'+index,
         q1: $("#q1").val(),
         q2: $(".form-check-input:checked").val(),
         q3: $("#q3").val()
	  },
      
	  success: function (data) {
        console.log(data.trim());
        
        var info = data.trim().split("=");
        phase = info[0];
        upload_cnt_obj1 = parseInt(info[1]);
        upload_cnt_obj2 = parseInt(info[2]);
        upload_cnt_obj3 = parseInt(info[3]);
        subset_cnt_obj = parseInt(info[4]);
        subset_cnt_num = parseInt(info[5]);
        obj_name = restoreSpecial(info[6]);
        obj_index = parseInt(info[7]);
        label = info[8];
        update_interface();
	  },
	  error: function () { console.log('fail'); }
	}).done(function(o) {
	  console.log('done'); 
	});
}

var phase = '';
var upload_cnt_obj1 = 0;
var upload_cnt_obj2 = 0;
var upload_cnt_obj3 = 0;
var subset_cnt_obj = 0;
var subset_cnt_num = 0;
var obj_name = 0;
var obj_index = 0;
var label = 0;
var subset_for = 'na';
var limit = -1;
var selected = [];
var selected_str = 'na';

var clickable = true;
var offScreenCanvas = null;
var offContext = null;
var canvas = null;
var context = null;
var w,h,ratio;
var videoElement = null;
var obj_colors = ['#76D7C4', '#FAD7A0', '#D7BDE2'];
var test_img_num = -1;
var train_img_num = -1;
function createOffscreenCanvas() {
    offScreenCanvas = document.createElement('canvas');
    offScreenCanvas.width = videoElement.videoWidth/2;
    offScreenCanvas.height = videoElement.videoHeight/2;
    offContext=offScreenCanvas.getContext('2d');
    //console.log(offScreenCanvas.width + ', ---- ' + offScreenCanvas.height);
    
	canvas=document.querySelector('#canvas');
	context=canvas.getContext('2d');
	ratio = offScreenCanvas.width/offScreenCanvas.height;
	w = offScreenCanvas.width/5;
	h = offScreenCanvas.height/5;
	//console.log(videoElement.videoWidth + ', ' + videoElement.videoHeight + ', '+ w + ', ' + h);
	
	canvas.width = w;
	canvas.height = h;
}

function show_prev_image() {
	console.log('set prev img');
	
	offContext.fillRect(0,0,videoElement.videoWidth/2,videoElement.videoHeight/2);
	offContext.drawImage(video,0,0,videoElement.videoWidth/2,videoElement.videoHeight/2);
	context.fillRect(0,0,w,h);
	context.drawImage(offScreenCanvas,0,0,w,h);
}
        
function get_random_object_test(test_img_num) {
	$objects = $("#objects");
	if (upload_cnt >= test_img_num*3) {
		$objects.text("Go to next step");
		end_overlay_on();
		return;
	}
	
	$("#count").text(test_img_num*3-upload_cnt);
	$objects.text(obj_names[obj_index[upload_cnt]-1]);
	videoElement.style.borderColor = document.getElementById("objects").style.backgroundColor = bgColors[obj_index[upload_cnt]-1];
}

function captureImage_test(test_img_num, phase) {
	if (!clickable) return;
	
	clickable = false;
	obj_counts[obj_index[upload_cnt]-1]++;
	upload_cnt++;
	get_random_object_test(test_img_num);
	show_prev_image(upload_cnt);
	$output = $("#output");
	$output.empty();
	
	var img = document.createElement("img");
	img.src = offScreenCanvas.toDataURL();
	
	$.ajax({
	  type: "POST",
	  url: "upload.php",
	  data: { 
		 imgBase64: img.src,
		 phase: phase,
		 obj_count: obj_counts[obj_index[upload_cnt-1]-1],
		 objectname: obj_names[obj_index[upload_cnt-1]-1],
		 ratio: ratio
	  },
	  success: function (data) {
		console.log('success'+data);

		$output = $("#output");
		$output.prepend(data);
		
		
		if (upload_cnt >= test_img_num*3) {
			//$("#nextButton").show();
			//document.getElementById("nextButton").scrollIntoView();
			return;
		} else {
			//document.getElementById("output").scrollIntoView();
			document.getElementById('interface').scrollTop = 10;
			document.getElementById("interface").scrollIntoView(true);
			clickable = true;
		}
		
	  },
	  error: function () { console.log('fail'); }
	}).done(function(o) {
	  console.log('done'); 
	});
}


function get_random_object_train(training_img_num) {
	$objects = $("#objects");
	if (upload_cnt >= training_img_num*3) {
		clickable = false;
		end_overlay_on();
	} else {
		start_overlay_on();
	}
	
	var step = Math.floor(upload_cnt/training_img_num);
	var obj_name = obj_names[obj_index[step]-1];
	
	$objects.text(obj_name);
	$("#obj_name1").text(obj_name);
	$("#obj_name2").text(obj_name);
	$("#count").text(training_img_num-upload_cnt % training_img_num);
	videoElement.style.borderColor = document.getElementById("objects").style.backgroundColor = bgColors[step];
}


function captureImage_train(training_img_num, phase) {
	if (!clickable) return;
	
	clickable = false;
	upload_cnt++;
	var obj_cnt = upload_cnt % training_img_num;
	show_prev_image();
	if (upload_cnt % training_img_num != 0) {
		$("#count").text(training_img_num-upload_cnt % training_img_num);
	} else {
		$("#count").text('0');
		get_random_object_train(training_img_num);
		obj_cnt = training_img_num;
	}
	
	var step = Math.floor((upload_cnt-1)/training_img_num);
	var obj_name = obj_names[obj_index[step]-1];
	
	console.log(upload_cnt + ", " + step + ", " + obj_index[step]);
	
	var img = document.createElement("img");
	img.src = offScreenCanvas.toDataURL();
	
	$.ajax({
	  type: "POST",
	  url: "upload.php",
	  data: { 
		 imgBase64: img.src,
		 phase: phase,
		 objectname: obj_name,
		 obj_count: obj_cnt
	  },
	  success: function (data) {
		console.log('success'+data);
		clickable = true;
	  },
	  error: function () { console.log('fail'); }
	}).done(function(o) {
	  console.log('done'); 
	});
}
