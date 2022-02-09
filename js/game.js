let forms = document.querySelectorAll("form");
let newGameBtn = document.querySelector(".new-game-btn"),
    joinGameBtn = document.querySelector(".join-game-btn");
let changingDiv = document.querySelector(".changing-div");
let mainDiv = document.querySelector(".main-div");
const templates = {
    joinGame: document.querySelector(".join-game-temp"),
    enterNick: document.querySelector(".enter-nick-temp")
}
for (const form of forms) {
    preventDefault(form);
}


newGameBtn.addEventListener('click', () => {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/game-start.php", true);
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                enterNick();
            }
        }
    }
    xhr.send();
});

function preventDefault(form) {
    form.addEventListener('submit', (e) => {
        e.preventDefault();
    });
}

joinGameBtn.addEventListener('click', () => {
    mainDiv.innerHTML = templates.joinGame.innerHTML;

    let joinIdBtn = mainDiv.querySelector(".join-id-btn");
    let joinForm = mainDiv.querySelector("form");
    let errorText = mainDiv.querySelector(".error-text");
    console.log(typeof (joinForm));
    preventDefault(joinForm);
    joinIdBtn.addEventListener('click', () => {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "php/game-join.php", true);
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

});

function enterNick() {
    mainDiv.innerHTML = templates.enterNick.innerHTML;
    let form = mainDiv.querySelector("form");
    let btn = form.querySelector("input[type=submit]");
    let errorText = mainDiv.querySelector(".error-text");
    preventDefault(form);
    btn.addEventListener('click', () => {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "php/game-nick.php", true);
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
    if (changingDiv.innerHTML !== mainDiv.innerHTML) {
        mainDiv.innerHTML = changingDiv.innerHTML;
        changingDiv.innerHTML = '';
    }
}

function generateScripts(toAppend, scriptsName) {
    for (const name of scriptsName) {
        let script = document.createElement("script");
        script.src = `./js/${name}`;
        toAppend.appendChild(script);
    }
}

function gameStart() {
    const loop = setInterval(() => {
        let xhr = new XMLHttpRequest();
        xhr.open("GET", "php/game-status.php", true);
        xhr.onload = () => {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    changingDiv.innerHTML = xhr.response;
                }
            }
        }
        xhr.send();

        updateMainDiv();

    }, 500);
    let stopIntervalBtn = document.querySelector('.stop-interval-btn');
    stopIntervalBtn.addEventListener('click', () => {
        clearInterval(loop);
    });
}


// // co się dzieje przed zamknięciem witryny
// window.onbeforeunload = (e) =>{
//     e.preventDefault();
//     e.returnValue = exitConfirm();
// };

