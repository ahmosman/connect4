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
    let xhr = new XMLHttpRequest()
    xhr.open('POST', 'php/GameEvents/game-start.php', true)
    xhr.onload = () => {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          if (xhr.response === 'success') {
            playerSetup()
          } else {
            console.log(xhr.response)
          }
        }
      }
    }
    xhr.send()
  } else if (e.target.classList.contains('join-game-btn')) {
    main.innerHTML = templates.joinGame.innerHTML

    let joinIdBtn = main.querySelector('.join-id-btn')
    let joinForm = main.querySelector('form')
    let errorText = main.querySelector('.error-text')
    preventDefault(joinForm)
    joinIdBtn.addEventListener('click', () => {
      let xhr = new XMLHttpRequest()
      xhr.open('POST', 'php/GameEvents/game-join.php', true)
      xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            if (xhr.response === 'success') {
              playerSetup()
            } else {
              errorText.innerHTML = xhr.response
            }
          }
        }
      }
      let formData = new FormData(joinForm)
      xhr.send(formData)
    })

  } else if (e.target.classList.contains('manual-btn')) {
    main.innerHTML = templates.manual.innerHTML
  } else if (e.target.classList.contains('empty-ball')) {
    putBall(e.target)
  }
})

function preventDefault (form) {
  form.addEventListener('submit', (e) => {
    e.preventDefault()
  })
}

function playerSetup () {
  main.innerHTML = templates.playerSetup.innerHTML
  let form = main.querySelector('form')
  preventDefault(form)
  let btn = form.querySelector('input[type=submit]')
  let errorText = main.querySelector('.error-text')
  let colorPlayerIn = form.querySelector('.color-player-input')
  let colorOpponentIn = form.querySelector('.color-opponent-input')
  const rgb2hex = (rgb) => `#${rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/).slice(1).map(n => parseInt(n, 10).toString(16).padStart(2, '0')).join('')}`

  btn.addEventListener('click', () => {

    //ustawienie kolorów w formularzu
    let pickedPlayerColor = form.querySelector('.pick-color-div .picked-player')
    let pickedOpponentColor = form.querySelector('.pick-color-div .picked-opponent')
    colorPlayerIn.value = rgb2hex(window.getComputedStyle(pickedPlayerColor).backgroundColor)
    colorOpponentIn.value = rgb2hex(window.getComputedStyle(pickedOpponentColor).backgroundColor)

    let xhr = new XMLHttpRequest()
    xhr.open('POST', 'php/GameEvents/game-player-setup.php', true)
    xhr.onload = () => {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          if (xhr.response === 'success') {
            gameLoop()
          } else {
            errorText.innerHTML = xhr.response
          }
        }
      }
    }
    let formData = new FormData(form)
    xhr.send(formData)

  })
}

function dynamicStyles () {
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

function updateMainDiv () {
  let xhr = new XMLHttpRequest()
  xhr.open('GET', 'php/GameEvents/game-content.php', true)
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        main.innerHTML = xhr.response
        dynamicStyles()
      }
    }
  }
  xhr.send()
}

function gameLoop () {
  const loop = setInterval(() => {
    // gdy zmieni się status gracza zostanie uruchomiona funkcja updateManDiv
    let xhr = new XMLHttpRequest()
    xhr.open('GET', 'php/GameEvents/game-players-status.php', true)
    xhr.onload = () => {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {

          let newStatus = JSON.parse(xhr.response)

          if (newStatus.player !== playerStatus || newStatus.opponent !== opponentStatus) {
            playerStatus = newStatus.player
            opponentStatus = newStatus.opponent
            updateMainDiv()
          }

        }
      }
    }
    xhr.send()

  }, 500)
  let stopIntervalBtn = document.querySelector('.stop-interval-btn')
  if (stopIntervalBtn) {
    stopIntervalBtn.addEventListener('click', () => {
      clearInterval(loop)
    })
  }
}

function putBall (ball) {
  let column = ball.dataset.col
  let xhr = new XMLHttpRequest()
  xhr.open('GET', `php/GameEvents/game-put-ball.php?col=${column}`, true)
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        // if(xhr.response === 'success')
        //     console.log('put ok');
        // else
        //     console.log('put error');

      }
    }
  }
  xhr.send()
}

// // co się dzieje przed zamknięciem witryny
// window.onbeforeunload = (e) =>{
//     e.preventDefault();
//     e.returnValue = exitConfirm();
// };

