
document.addEventListener('click',(e)=>{
    if(e.target.classList.contains('btn-confirm'))
    {
        joinConfirm();
    }
});


function joinConfirm() {

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/game-confirm.php", true);
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                if (!(xhr.response === "success"))
                    console.log(xhr.response);
            }
        }
    }
    xhr.send();
}

function wantRevenge() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/game-revenge.php", true);
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                if (!(xhr.response === "success"))
                    console.log(xhr.response);
            }
        }
    }
    xhr.send();
}

function exitConfirm() {
    if (confirm("Czy chcesz opuścić grę?")) {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "php/game-disconnect.php", true);
        xhr.onload = () => {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    if (!(xhr.response === "success"))
                        console.log(xhr.response);
                }
            }
        }
        xhr.send();
        return true;
    }
    return false;
}