
var gameState = {
    player: 'X',
    board: [0, 0, 0, 0, 0, 0, 0, 0, 0]
}

function cellClick(locationNumber) {
    if (gameState.board[locationNumber] === 0) {
        gameState.board[locationNumber] = gameState.player
        var text = document.createTextNode(gameState.player)
        document.querySelector('#location-' + locationNumber).appendChild(text)

        if (checkVictory(gameState)) {
            alert(gameState.player + ' wins!')
        } else if (checkTie()) {
            alert('Tie!')
        }

        if (gameState.player === 'X') {
            gameState.player = 'O'
        } else {
            gameState.player = 'X'
        }
    }
}

function checkTie() {
    for (var i = 0; i < gameState.board.length; i++) {
        if (gameState.board[i] === 0) {
            return false;
        }
    }

    return true;
}

function checkVictory(gameState) {
    var boardState = gameState.board
    var player = gameState.player
    if (boardState[0] === player &&
        boardState[1] === player &&
        boardState[2] === player) {
        return true
    } else if (boardState[3] === player &&
        boardState[4] === player &&
        boardState[5] === player) {
        return true
    } else if (boardState[6] === player &&
        boardState[7] === player &&
        boardState[8] === player) {
        return true
    } else if (boardState[0] === player &&
        boardState[3] === player &&
        boardState[6] === player) {
        return true
    } else if (boardState[1] === player &&
        boardState[4] === player &&
        boardState[7] === player) {
        return true
    } else if (boardState[2] === player &&
        boardState[5] === player &&
        boardState[8] === player) {
        return true
    } else if (boardState[0] === player &&
        boardState[4] === player &&
        boardState[8] === player) {
        return true
    } else if (boardState[2] === player &&
        boardState[4] === player &&
        boardState[6] === player) {
        return true
    }
    else {
        return false
    }
}
