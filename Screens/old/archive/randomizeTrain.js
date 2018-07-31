var obj1 = {"id":"obj1","count":"0"};
var obj2 = {"id":"obj2","count":"0"};
var obj3 = {"id":"obj3","count":"0"};
var button = {"id":"uploadButton","count":"0"};
var arr = [obj1, obj2, obj3];

// For "Get Object" button; selects a random object for user to train
function randomize() {

	// Hides the previous object
	for (var i = 0; i < arr.length; i++) {
		document.getElementById(arr[i].id).style.display = "none";
	}
	var randObj = arr[Math.floor(Math.random() * arr.length)];
	// Ensures that each object is only called once
	while (randObj.count > 0) {
		randObj = arr[Math.floor(Math.random() * arr.length)];
	}
	randObj.count++;

	document.getElementById(randObj.id).style.display = "block";

	// Hides the button after new object is shown
	document.getElementById("objButton").style.display = "none";

}

// For "Upload" button; counts number of images trained for each object and uploads images to server (eventually)
function countPics() {

	button.count++;

	if (button.count < 90) {
		// Allows user to train new object after 30 images
		if (button.count % 30 === 0) {
			document.getElementById("objButton").style.display = "block";
		}
	}
	// Ensures user only trains 90 images total
	else {
		document.getElementById(button.id).style.display = "none";
		document.getElementById("wholeCounter").innerHTML = "You're done! Please move on to the next page."
	}

	// Displays the number of images uploaded
	document.getElementById("counter").innerHTML = button.count % 30;

}

// For "Take a Picture" button
function takePic() {
	document.getElementById("fileToUpload").click();
}




