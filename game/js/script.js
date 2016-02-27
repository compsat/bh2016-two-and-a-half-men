var canvas;
var gl;

window.onload = function() {
	canvas = document.getElementById("canvas");
	gl = canvas.getContext("webgl");
	if(gl == undefined) {
		gl = canvas.getContext("experimental-webgl");
	}
	
	window.onresize(null);
	
	gl.clearColor(0.3, 0.3, 0.3, 1.0);
	
	lastUpdate = Date.now();
	requestAnimationFrame(render);
}

var lastUpdate;
function render() {
	requestAnimationFrame(render);	
	
	var delta = (Date.now() - lastUpdate) / 1000;
	lastUpdate = Date.now();
	
	gl.clear(gl.COLOR_BUFFER_BIT | gl.DEPTH_BUFFER_BIT);
}

window.onresize = function() {
	canvas.width = window.innerWidth;
	canvas.height = window.innerHeight;
}