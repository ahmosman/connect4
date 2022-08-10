import {gameLoop} from './game.js'
import {makeRequest} from './ajaxRequests.js'

let testPlayerBtn = document.querySelector('.test-player-btn')
let testPlayerForm = document.querySelector('footer form')
testPlayerBtn.addEventListener('click', () => {
    makeRequest('POST', 'game-test.php', gameLoop, testPlayerForm)
})