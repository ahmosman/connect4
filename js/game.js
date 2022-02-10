let forms = document.querySelectorAll("form");
let mainDiv = document.querySelector(".main-div");
const templates = {
    joinGame: document.querySelector(".join-game-temp"),
    enterNick: document.querySelector(".enter-nick-temp")
}
let playerStatus = null;
let opponentStatus = null;
for (const form of forms) {
    preventDefault(form);
}
// ********* TESTING
// gameStart();
// ********* TESTING

document.addEventListener('click', (e) => {
    if (e.target.classList.contains('new-game-btn')) {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "php/GameEvents/game-start.php", true);
        xhr.onload = () => {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    if (xhr.response === "success") {
                        enterNick();
                    } else {
                        console.log(xhr.response);
                    }
                }
            }
        }
        xhr.send();
    } else if (e.target.classList.contains('join-game-btn')) {
        mainDiv.innerHTML = templates.joinGame.innerHTML;

        let joinIdBtn = mainDiv.querySelector(".join-id-btn");
        let joinForm = mainDiv.querySelector("form");
        let errorText = mainDiv.querySelector(".error-text");
        preventDefault(joinForm);
        joinIdBtn.addEventListener('click', () => {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "php/GameEvents/game-join.php", true);
            xhr.onload = () => {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        if (xhr.response === "success") {
                            enterNick();
                        } else {
                            errorText.innerHTML = xhr.response;
                        }
                    }
                }
            }
            let formData = new FormData(joinForm);
            xhr.send(formData);
        });

    } else if (e.target.classList.contains('empty-ball')) {
        putBall(e.target);
    }
});

function preventDefault(form) {
    form.addEventListener('submit', (e) => {
        e.preventDefault();
    });
}

function enterNick() {
    mainDiv.innerHTML = templates.enterNick.innerHTML;
    let form = mainDiv.querySelector("form");
    let btn = form.querySelector("input[type=submit]");
    let errorText = mainDiv.querySelector(".error-text");
    preventDefault(form);
    btn.addEventListener('click', () => {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "php/GameEvents/game-nick.php", true);
        xhr.onload = () => {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    if (xhr.response === "success") {
                        gameStart();
                    } else {
                        errorText.innerHTML = xhr.response;
                    }
                }
            }
        }
        let formData = new FormData(form);
        xhr.send(formData);

    });
}

function updateMainDiv() {
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "php/GameEvents/game-content.php", true);
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                mainDiv.innerHTML = xhr.response;
            }
        }
    }
    xhr.send();
}

function gameStart() {
    const loop = setInterval(() => {
        // gdy zmieni się status gracza zostanie uruchomiona funkcja updateManDiv
        let xhr = new XMLHttpRequest();
        xhr.open("GET", "php/GameEvents/game-players-status.php", true);
        xhr.onload = () => {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {

                    let newStatus = JSON.parse(xhr.response);
                    if (newStatus.player !== playerStatus || newStatus.opponent !== opponentStatus) {
                        playerStatus = newStatus.player;
                        opponentStatus = newStatus.opponent;
                        updateMainDiv();
                    }

                }
            }
        }
        xhr.send();

    }, 500);
    let stopIntervalBtn = document.querySelector('.stop-interval-btn');
    stopIntervalBtn.addEventListener('click', () => {
        clearInterval(loop);
    });
}

function putBall(ball) {
    let column = ball.dataset.col;
    let xhr = new XMLHttpRequest();
    xhr.open("GET", `php/GameEvents/game-put-ball.php?col=${column}`, true);
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                console.log(xhr.response);
                // if(xhr.response === 'success')
                //     console.log('put ok');
                // else
                //     console.log('put error');

            }
        }
    }
    xhr.send();
}

// // co się dzieje przed zamknięciem witryny
// window.onbeforeunload = (e) =>{
//     e.preventDefault();
//     e.returnValue = exitConfirm();
// };

