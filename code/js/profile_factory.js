/**
 * Created by Alex on 1/15/2016.
 */
function generateProfile(initials, id, ratio) {
    var colours = ["#1abc9c", "#2ecc71", "#3498db", "#9b59b6", "#34495e",
        "#16a085", "#27ae60", "#2980b9", "#8e44ad", "#2c3e50", "#f1c40f",
        "#e67e22", "#e74c3c", "#95a5a6", "#f39c12", "#d35400", "#c0392b",
        "#bdc3c7", "#7f8c8d"];

    var charIndex = initials.charCodeAt(0) - 65,
        colourIndex = charIndex % 19;

    var canvas = document.getElementById(id);
    var context = canvas.getContext("2d");
    var canvasWidth = $(canvas).attr("width"),
        canvasHeight = $(canvas).attr("height"),
        canvasCssWidth = canvasWidth,
        canvasCssHeight = canvasHeight;

    if (ratio) {
        $(canvas).attr("width", canvasWidth * ratio);
        $(canvas).attr("height", canvasHeight * ratio);
        $(canvas).css("width", canvasCssWidth);
        $(canvas).css("height", canvasCssHeight);
        context.scale(ratio, ratio);
    }

    context.fillStyle = colours[colourIndex];
    context.fillRect(0, 0, canvas.width, canvas.height);
    context.font = "25px Arial";
    context.textAlign = "center";
    context.fillStyle = "#FFF";
    context.fillText(initials, canvasCssWidth / 2, canvasCssHeight / 1.3);
}