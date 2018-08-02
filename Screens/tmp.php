<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta content="stuff, to, help, search, engines, not" name="keywords">
<meta content="What this page is about." name="description">
<meta content="Display Webcam Stream" name="title">
<title>Display Webcam Stream</title>
  
<style>
#container {
    margin: 0px auto;
    width: 300px;
    height: 300px;
    border: 10px #333 solid;
}
#videoElement {
    width: 300px;
    height: 300px;
    background-color: #666;
}
</style>
</head>
  
<body>
<div id="container">
    <video autoplay="true" id="videoElement">
     
    </video>
</div>
<script>
 var video = document.querySelector("#videoElement");
 
if (navigator.mediaDevices.getUserMedia) {       
    navigator.mediaDevices.getUserMedia({video: true})
  .then(function(stream) {
    video.srcObject = stream;
  })
  .catch(function(err0r) {
    console.log("Something went wrong!");
  });
}
</script>
<canvas id="canvas" width="150" height="150"></canvas>
</body>
</html>