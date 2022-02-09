let testPlayerBtn = document.querySelector(".test-player-btn");
let testPlayerForm =  document.querySelector("footer form");
testPlayerBtn.addEventListener('click',()=>{
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/game-test.php", true);
    xhr.onload = ()=> {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                if(xhr.response === 'success')
                    gameStart();
                else
                    console.log(xhr.response);
            }
        }
    }
    let formData = new FormData(testPlayerForm);
    xhr.send(formData);
});