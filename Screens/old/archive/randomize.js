var arr = [{"id":"obj1","count":"0"}, {"id":"obj2","count":"0"}, {"id":"obj3","count":"0"}];

function randomize() {
	for (var i = 0; i < arr.length; i++) {
		document.getElementById(arr[i].id).style.display = "none";
	}
	var randObj = arr[Math.floor(Math.random() * arr.length)];
	randObj.count++;
	document.getElementById(randObj.id).style.display = "block";
    // document.getElementById("count").innerHTML = randObj.count;
}
