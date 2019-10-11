
var gameState = [0, 0, 0, 0, 0, 0, 0, 0, 0]

/*
document.querySelector('.cell').addEventListener('click', function(){

});
*/
function cellClick(locationNumber) {
    var text = document.createTextNode("X");
    document.querySelector('#location-' + locationNumber).appendChild(text);
}

function makeMove(location) {
    gameState[location] = 'X'
}