var canvas = document.getElementById('canv'),
    ctx = canvas.getContext('2d'),
    img = document.getElementById('img-A'),
    myText = "This morning he definitely wants some coffee.";

function grow(el) {
    el.style.height = "10rem";
    el.style.height = (el.scrollHeight) + "px";
}

var generate = function () {
    var text = myText.split("\n").join("\n");
    var x = 12.5;
    var y = 15;
    var lineheight = 30;
    var lines = text.split('\n');
    var lineLengthOrder = lines.slice(0).sort(function (a, b) {
        return b.length - a.length;
    });
    ctx.canvas.width = ctx.measureText(lineLengthOrder[0]).width + 25;
    ctx.canvas.height = (lines.length * lineheight);

    ctx.fillStyle = "#ffffff";
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    ctx.textBaseline = "middle";
    ctx.font = "16px Anonymous Pro";
    ctx.fillStyle = "#272727";
    for (var i = 0; i < lines.length; i++)
        ctx.fillText(lines[i], x, y + (i * lineheight));
    img.src = ctx.canvas.toDataURL();
}
// document.getElementById('submit').addEventListener('click', function (){
// 	document.getElementById("image").style.display = 'block';
// 	generate();
// });

generate();
