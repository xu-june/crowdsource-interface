var obj1 = {"id":"obj1","count":"0"};
var obj2 = {"id":"obj2","count":"0"};
var obj3 = {"id":"obj3","count":"0"};
// var obj1 = {"id":"object1","count":"0"};
// var obj2 = {"id":"object2","count":"0"};
// var obj3 = {"id":"object3","count":"0"};
var button = {"id":"objButton","count":"0"};
var arr = [obj1, obj2, obj3];

// For "Get Object" button; selects a random object for user to test
function randomize() {

	button.count++;
	// Hides the previous object
	for (var i = 0; i < arr.length; i++) {
		document.getElementById(arr[i].id).style.display = "none";
	}
	var randObj = arr[Math.floor(Math.random() * arr.length)];
	// Ensures each object can only be called 5 times
	while (randObj.count + 1 > 5) {
		randObj = arr[Math.floor(Math.random() * arr.length)];
	}
	randObj.count++;
	
	// document.getElementById("object").innerHTML = document.getElementById(randObj.id).value;
	document.getElementById(randObj.id).style.display = "block";

	// Hides the button after all objects are called 5 times
	if (button.count >= 15) {
		document.getElementById(button.id).style.display = "none";
	}

}

// For "Take a Picture" button
function takePic() {
	document.getElementById("fileToUpload").click();
}




