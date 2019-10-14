AI_GOES_FIRST = false;

var GAME_STATE = {
    player: 'X',
    board: [
         0,   0,   0,
         0,   0,   0,
         0,   0,   0],
    gameResult: 'game is not over' 
}

for (var i = 0; i < GAME_STATE.board.length; i++) {
    var player = GAME_STATE.board[i]
    if (player !== 0) {
        drawLetter(i, player)
    }
}

function drawLetter(move, letter) {
    var text = document.createTextNode(letter)
    document.querySelector('#cell-' + move).appendChild(text)
}


function cellClick(move) {
    if (GAME_STATE.gameResult === 'game is not over' && 
        GAME_STATE.board[move] === 0) {
        
        makeMove(move)

        if (GAME_STATE.gameResult === 'game is not over') {
            aiMove()
        }
    }
}

function aiMove() {
    move = getBestMove(GAME_STATE).move
    makeMove(move)
}

function makeMove(move) {
    GAME_STATE.board[move] = GAME_STATE.player

    drawLetter(move, GAME_STATE.player)

    GAME_STATE.gameResult = getGameResult(GAME_STATE)

    if (GAME_STATE.gameResult === 'tie') {
        alert('Tie!')
    } else if (GAME_STATE.gameResult === 'X') {
        alert('X wins!')
    } else if (GAME_STATE.gameResult === 'O') {
        alert('O wins!')
    } else {
        switchPlayer()
    }
}

function switchPlayer() {
    if (GAME_STATE.player === 'X') {
        GAME_STATE.player = 'O'
    } else {
        GAME_STATE.player = 'X'
    }
}

function getGameResult() {
    if (isTie(GAME_STATE)) {
        return 'tie'
    } else if (isVictory(GAME_STATE.board, 'X')) {
        return 'X'
    } else if (isVictory(GAME_STATE.board, 'O')) {
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

if (AI_GOES_FIRST) {
    aiMove()
}

