function drawCanvasMenu() {
    var canvas = document.querySelector("#cabinetMenuCanvas")
    if (canvas) {
        var context = canvas.getContext("2d")
        context.fillStyle = "#F7F8FA";

        context.beginPath();
        context.moveTo(0, canvas.height);
        context.quadraticCurveTo(canvas.height, 0, canvas.width, canvas.height);
        context.closePath();
        context.fill();
    }
}