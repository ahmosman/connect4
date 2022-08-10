import {makeRequest} from "./ajaxRequests.js"

let forms = document.querySelectorAll('form')
let main = document.querySelector('.main-section')
const templates = {
    joinGame: document.querySelector('.join-game-temp'),
    playerSetup: document.querySelector('.player-setup-temp'),
    manual: document.querySelector('.manual-temp')
}
let playerStatus = null
let opponentStatus = null
for (const form of forms) {
    preventDefault(form)
}
// ********* TESTING
// gameStart();
// ********* TESTING

document.addEventListener('click', (e) => {
    if (document.querySelector('.result-heading'))
        return
    if (e.target.classList.contains('new-game-btn')) {
        makeRequest('POST', 'game-start.php', playerSetup)

    } else if (e.target.classList.contains('join-game-btn')) {
        main.innerHTML = templates.joinGame.innerHTML

        let joinIdBtn = main.querySelector('.join-id-btn')
        let joinForm = main.querySelector('form')
        preventDefault(joinForm)
        joinIdBtn.addEventListener('click', () => {
            makeRequest('POST', 'game-join.php', playerSetup, joinForm)
        })

    } else if (e.target.classList.contains('manual-btn')) {
        main.innerHTML = templates.manual.innerHTML
    } else if (e.target.classList.contains('empty-ball')) {
        putBall(e.target)
    }
})

function preventDefault(form) {
    form.addEventListener('submit', (e) => {
        e.preventDefault()
    })
}

function playerSetup() {
    main.innerHTML = templates.playerSetup.innerHTML
    let form = main.querySelector('form')
    preventDefault(form)
    let btn = form.querySelector('input[type=submit]')
    let colorPlayerIn = form.querySelector('.color-player-input')
    let colorOpponentIn = form.querySelector('.color-opponent-input')
    const rgb2hex = (rgb) => `#${rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/).slice(1).map(n => parseInt(n, 10).toString(16).padStart(2, '0')).join('')}`

    btn.addEventListener('click', () => {

        //ustawienie kolorów w formularzu
        let pickedPlayerColor = form.querySelector('.pick-color-div .picked-player')
        let pickedOpponentColor = form.querySelector('.pick-color-div .picked-opponent')
        colorPlayerIn.value = rgb2hex(window.getComputedStyle(pickedPlayerColor).backgroundColor)
        colorOpponentIn.value = rgb2hex(window.getComputedStyle(pickedOpponentColor).backgroundColor)

        makeRequest('POST', 'game-player-setup.php', gameLoop, form)

    })
}

function dynamicStyles() {
    let board = document.querySelector('.board')
    let side

    if (window.screen.width < window.screen.height) {
        side = window.screen.width * 0.9
    } else {
        side = window.screen.height * 0.8
    }
    if (board) {
        board.style.height = `${side}px`
        board.style.width = `${side}px`
    }
}

function updateMainDiv(content) {
    main.innerHTML = content
    dynamicStyles()
}

function updatePlayersStatus(newStatusJson) {
    // gdy zmieni się status gracza zostanie uruchomiona funkcja updateManDiv
    const newStatus = JSON.parse(newStatusJson)

    if (newStatus.player !== playerStatus || newStatus.opponent !== opponentStatus) {
        playerStatus = newStatus.player
        opponentStatus = newStatus.opponent
        makeRequest('GET', 'game-content.php', updateMainDiv)
    }
}

export function gameLoop() {
    const loop = setInterval(() => {
        makeRequest('GET', 'game-players-status.php', updatePlayersStatus)

    }, 500)
    let stopIntervalBtn = document.querySelector('.stop-interval-btn')
    if (stopIntervalBtn) {
        stopIntervalBtn.addEventListener('click', () => {
            clearInterval(loop)
        })
    }
}

function putBall(ball) {
    let column = ball.dataset.col
    makeRequest('GET', `game-put-ball.php?col=${column}`)
}

