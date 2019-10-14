
var gameState = {
    player: 'X',
    board: [
         0,   0,  0,
         0,   0,  0,
         0,   0,  0],
    gameResult: 'game is not over' 
}

for (var i = 0; i < gameState.board.length; i++) {
    var player = gameState.board[i]
    if (player !== 0) {
        drawLetter(i, player)
    }
}

function drawLetter(location, letter) {
    var text = document.createTextNode(letter)
    document.querySelector('#location-' + location).appendChild(text)
}

aiMove()


function cellClick(locationNumber) {
    if (gameState.gameResult !== 'game is not over') {
        return
    }

    if (gameState.board[locationNumber] === 0) {
        gameState.board[locationNumber] = gameState.player
        
        drawLetter(locationNumber, gameState.player)

        gameState.gameResult = getGameResult(gameState)

        if (gameState.gameResult === 'tie') {
            alert('Tie!')
        } else if (gameState.gameResult === 'X') {
            alert('X wins!')
        } else if (gameState.gameResult === 'O') {
            alert('O wins!')
        } else {
            switchPlayer(gameState)
        }

        if (gameState.gameResult !== 'game is not over') {
            return
        }

        aiMove()
    }
}

function aiMove() {
    locationNumber = getBestMove(gameState).move
    gameState.board[locationNumber] = gameState.player
    
    var text = document.createTextNode(gameState.player)
    document.querySelector('#location-' + locationNumber).appendChild(text)

    var gameResult = getGameResult(gameState)

    if (gameResult === 'tie') {
        alert('Tie!')
    } else if (gameResult === 'X') {
        alert('X wins!')
    } else if (gameResult === 'O') {
        alert('O wins!')
    } else {
        switchPlayer(gameState)
    }
}

function switchPlayer(gameState) {
    if (gameState.player === 'X') {
        gameState.player = 'O'
    } else {
        gameState.player = 'X'
    }
}

function getGameResult(gameState) {
    if (isTie(gameState)) {
        return 'tie'
    } else if (isVictory(gameState.board, 'X')) {
        return 'X'
    } else if (isVictory(gameState.board, 'O')) {
        return 'O'
    } else {
        return 'game is not over' 
    }
}

function isTie(gameState) {
    for (var i = 0; i < gameState.board.length; i++) {
        if (gameState.board[i] === 0) {
            return false
        }
    }

    return true
}

function isVictory(board, player) {
    if (board[0] === player &&
        board[1] === player &&
        board[2] === player) {
        return true
    } else if (board[3] === player &&
        board[4] === player &&
        board[5] === player) {
        return true
    } else if (board[6] === player &&
        board[7] === player &&
        board[8] === player) {
        return true
    } else if (board[0] === player &&
        board[3] === player &&
        board[6] === player) {
        return true
    } else if (board[1] === player &&
        board[4] === player &&
        board[7] === player) {
        return true
    } else if (board[2] === player &&
        board[5] === player &&
        board[8] === player) {
        return true
    } else if (board[0] === player &&
        board[4] === player &&
        board[8] === player) {
        return true
    } else if (board[2] === player &&
        board[4] === player &&
        board[6] === player) {
        return true
    }
    else {
        return false
    }
}

function cloneGameState(gameState) {
    var newBoard = gameState.board.slice(0)
    return {
        player: gameState.player,
        board: newBoard
    }
}

// Assuming it is X's turn
function getBestMove(gameState) {

    var bestMove = undefined

    var otherPlayer
    if (gameState.player === 'X') {
        otherPlayer = 'O'
    } else {
        otherPlayer = 'X'
    }

    for (var i = 0; i < gameState.board.length; i++) {
        if (gameState.board[i] === 0) {
            var clone = cloneGameState(gameState)
            clone.board[i] = gameState.player
            if (isVictory(clone.board, gameState.player)) {
                return {
                    victor: gameState.player,
                    move: i
                }
            } else if (isTie(clone)) {
                bestMove = {
                    victor: 'tie',
                    move: i
                }
            } else {
                clone.player = otherPlayer
                var result = getBestMove(clone)

                if (result.victor === gameState.player) {
                    return {
                        victor: gameState.player,
                        move: i
                    }
                } else if (result.victor === 'tie') {
                    bestMove = {
                        victor: 'tie',
                        move: i
                    }
                } else if (bestMove === undefined) {
                    bestMove = {
                        victor: otherPlayer,
                        move: i
                    }
                }
            }
        }
    }

    return bestMove
}
