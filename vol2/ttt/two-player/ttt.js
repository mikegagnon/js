
var currentPlayer = 'X'
var boardState = [0, 0, 0, 0, 0, 0, 0, 0, 0]

function cellClick(locationNumber) {
    if (boardState[locationNumber] === 0) {
        boardState[locationNumber] = currentPlayer
        var text = document.createTextNode(currentPlayer)
        document.querySelector('#location-' + locationNumber).appendChild(text)

        if (checkVictory(currentPlayer)) {
            alert(currentPlayer + ' wins!')
        } else if (checkTie()) {
            alert('Tie!')
        }

        if (currentPlayer === 'X') {
            currentPlayer = 'O'
        } else {
            currentPlayer = 'X'
        }
    }
}

function checkTie() {
    for (var i = 0; i < boardState.length; i++) {
        if (boardState[i] === 0) {
            return false;
        }
    }

    return true;
}

function checkVictory(player) {
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
