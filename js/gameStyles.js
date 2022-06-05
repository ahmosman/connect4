document.addEventListener('mouseover', (e) => {

    if (document.querySelector(".result-heading"))
        return;
    if (e.target.classList.contains('empty-ball')) {
        let emptyBalls = document.querySelectorAll('.empty-ball');
        let column = e.target.dataset.col;
        for (const ball of emptyBalls) {
            if (ball.dataset.col === column) {
                ball.classList.add('ball-hover');
            }
        }
    }
});
document.addEventListener('mouseout', (e) => {

    if (e.target.classList.contains('empty-ball')) {
        let emptyBalls = document.querySelectorAll('.empty-ball');
        for (const ball of emptyBalls)
            ball.classList.remove('ball-hover');
    }
});

// Wybór koloru graczy
document.addEventListener('click', (e) => {
    if (e.target.classList.contains('color') && !e.target.classList.contains('picked-player') && !e.target.classList.contains('picked-opponent')) {
        let pickColorDiv = e.target.parentElement;
        let anotherPickColorDiv;
        let anotherCurrentPickedClass;
        let currentPickedClass;
        if (pickColorDiv.classList.contains('pick-color-player')) {

            anotherPickColorDiv = document.querySelector(".pick-color-opponent");
            currentPickedClass = "picked-player";
            anotherCurrentPickedClass = "picked-opponent";

        } else if (pickColorDiv.classList.contains('pick-color-opponent')) {

            anotherPickColorDiv = document.querySelector(".pick-color-player");
            currentPickedClass = "picked-opponent";
            anotherCurrentPickedClass = "picked-player";
        }
        let currentPicked = pickColorDiv.querySelector(`.${currentPickedClass}`);
        currentPicked.classList.remove(currentPickedClass);
        e.target.classList.add(currentPickedClass);

        let currentAnotherPicked = anotherPickColorDiv.querySelector(`.${anotherCurrentPickedClass}`);

        if (e.target.classList[1] === currentAnotherPicked.classList[1]) {
            currentAnotherPicked.classList.remove(anotherCurrentPickedClass);
            let newAnotherPicked = anotherPickColorDiv.querySelector(`.${currentPicked.classList[1]}`);
            newAnotherPicked.classList.add(anotherCurrentPickedClass);
        }
    }
});