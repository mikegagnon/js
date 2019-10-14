function drawLetter(cellNumber, letter) {
    var text = document.createTextNode(letter)
    var cellId = 'cell-' + cellNumber
    var cellRef = document.getElementById(cellId)
    cellRef.appendChild(text)
}

function cellClick(move) {
    if (GAME_STATE.gameResult === 'game is not over' && 
        GAME_STATE.board[move] === '-') {
        
        makeMove(move)

        if (MODE !== 'Two player' &&
            GAME_STATE.gameResult === 'game is not over') {
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
    } else if (isVictory(GAME_STATE)) {
        return GAME_STATE.player
    } else {
        return 'game is not over' 
    }
}

function isTie(gameState) {
    for (var i = 0; i < gameState.board.length; i++) {
        if (gameState.board[i] === '-') {
            return false
        }
    }

    return true
}

function isVictory(gameState) {
    var board = gameState.board
    var player = gameState.player
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
        if (gameState.board[i] === '-') {
            var clone = cloneGameState(gameState)
            clone.board[i] = gameState.player
            if (isVictory(clone)) {
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

function drawInitialBoard() {
    for (var i = 0; i < GAME_STATE.board.length; i++) {
        var move = GAME_STATE.board[i]
        if (move !== '-') {
            drawLetter(i, move)
        }
    }
}

var GAME_STATE = {
    player: 'X',
    board: [
        '-', '-', '-',
        '-', '-', '-',
        '-', '-', '-'],
    gameResult: 'game is not over' 
}

var MODE = 'Single player'
var AI_GOES_FIRST = true;

if (MODE === 'Test defense') {
    GAME_STATE.board = [
        'O', '-', '-',
        '-', 'X', '-',
        '-', '-', 'X']
    drawInitialBoard()
    GAME_STATE.player = 'O'
    aiMove()
} else if (MODE === 'Test offense 1') {
    GAME_STATE.board = [
        '-', '-', '-',
        '-', '-', 'X',
        'X', 'O', 'O']
    drawInitialBoard()
    GAME_STATE.player = 'O'
    aiMove()
} else if (MODE === 'Test offense 2') {
    GAME_STATE.board = [
        '-', '-', '-',
        '-', '-', 'X',
        '-', '-', 'O']
    drawInitialBoard()
    GAME_STATE.player = 'O'
    aiMove()
} else if (MODE === 'Two player') {
    drawInitialBoard()
} else {
    drawInitialBoard()

    if (AI_GOES_FIRST) {
        aiMove()
    }    
}
