var cells = document.querySelectorAll("#adjustable-table td");

cells.forEach(function(cell) {
    var textLength = cell.innerText.length;
    var fontSize = 12 - (textLength * 0.1);
    cell.style.fontSize = fontSize + "px";
});