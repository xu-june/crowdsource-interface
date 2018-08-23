function submit_objects() {
	document.getElementById("submitbtn").click();
}
      
function start_overlay_on() {
	document.getElementById("start_overlay").style.display = "block";
	document.getElementById("preview").style.display = "none";
}

function start_overlay_off() {
	if (!clickable) return;
	document.getElementById("start_overlay").style.display = "none";
	document.getElementById("preview").style.display = "block";
}

function end_overlay_on() {
	document.getElementById("end_overlay").style.display = "block";
	document.getElementById("preview").style.display = "none";
}

function end_overlay_off() {
	document.getElementById("end_overlay").style.display = "none";
	document.getElementById("preview").style.display = "block";
}

var offScreenCanvas = null;
var offContext = null;
var canvas = null;
var context = null;
var w,h,ratio;
var videoElement = null;
function createOffscreenCanvas() {
    offScreenCanvas = document.createElement('canvas');
    offScreenCanvas.width = videoElement.videoWidth/2;
    offScreenCanvas.height = videoElement.videoHeight/2;
    offContext=offScreenCanvas.getContext('2d');
    console.log(offScreenCanvas.width + ', ---- ' + offScreenCanvas.height);
    
	canvas=document.querySelector('#canvas');
	context=canvas.getContext('2d');
	ratio = offScreenCanvas.width/offScreenCanvas.height;
	w = offScreenCanvas.width/5;
	h = offScreenCanvas.height/5;
	console.log(videoElement.videoWidth + ', ' + videoElement.videoHeight + ', '+ w + ', ' + h);
	
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

// 
// // Reference to native method(s)
// var oldLog = console.log;
// console.log = function( ...items ) {
//     // Call native method first
//     oldLog.apply(this,items);
// 
//     // Use JSON to transform objects, all others display normally
//     items.forEach( (item,i)=>{
//         items[i] = (typeof item === 'object' ? JSON.stringify(item,null,4) : item);
//     });
// 	// Reference to an output container, use 'pre' styling for JSON output
// 	var output = document.getElementById('log');
//     output.innerHTML += items.join(' ') + '<br />';
// };