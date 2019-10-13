
var gameState = {
    player: 'X',
    board: [0, 0, 0, 0, 0, 0, 0, 0, 0],
}

function cellClick(locationNumber) {
    if (gameState.board[locationNumber] === 0) {
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

        /*
        locationNumber = getBestMoveForO(gameState)
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
        */
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
/*
function cloneGameState(gameState) {
    var newBoard = gameState.board.slice(0)
    return {
        player: gameState.player,
        board: newBoard
    }
}

// Assuming it is X's turn
function getBestMoveForX(gameState) {

    var bestMove = undefined;

    for (var i = 0; i < gameState.board.length; i++) {
        if (gameState.board[i] === 0) {
            var clone = cloneGameState(gameState)
            clone.board[i] = 'X'
            clone.player = 'O'
            var result = getBestMoveForO(clone)
            if (result.victor === 'X') {
                return {
                    victor: 'X',
                    move: i
                }
            } else if (result.victor === 'tie') {
                bestMove = {
                    victor: 'tie',
                    move: i
                }
            } else if (bestMove === undefined) {
                bestMove = {
                    victor: 'O',
                    move: i
                }
            }
        }
    }

    return bestMove
}

// Assuming it is X's turn
function getBestMoveForO(gameState) {



    var bestMove = undefined;

    for (var i = 0; i < gameState.board.length; i++) {
        if (gameState.board[i] === 0) {
            var clone = cloneGameState(gameState)
            clone.board[i] = 'O'

            if (isVictory(clone)) {
                return 
            }

            clone.player = 'X'
            var result = getBestMoveForX(clone)
            if (result.victor === 'O') {
                return {
                    victor: 'O',
                    move: i
                }
            } else if (result.victor === 'tie') {
                bestMove = {
                    victor: 'tie',
                    move: i
                }
            } else if (bestMove === undefined) {
                bestMove = {
                    victor: 'X',
                    move: i
                }
            }
        }
    }

    return bestMove
}
*/