
var currentPlayer = 'X'
var boardState = [0, 0, 0, 0, 0, 0, 0, 0, 0]

function cellClick(locationNumber) {
    if (boardState[locationNumber] === 0) {
        boardState[locationNumber] = currentPlayer
        var text = document.createTextNode(currentPlayer)
        document.querySelector('#location-' + locationNumber).appendChild(text)
        if (currentPlayer === 'X') {
            currentPlayer = 'O'
        } else {
            currentPlayer = 'X'
        }
    }
}

function makeMove(location) {
    gameState[location] = 'X'
}