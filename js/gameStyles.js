document.addEventListener('mouseover', (e) => {

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

// TODO: możliwość wyboru koloru kulek gracza i przeciwnika