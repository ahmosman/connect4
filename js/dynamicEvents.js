import {makeRequest} from './ajaxRequests.js'
document.addEventListener('click', (e) => {
  if (e.target.classList.contains('btn-confirm')) {
    makeRequest('POST','game-confirm.php')
  }
  if (e.target.classList.contains('btn-revenge')) {
    makeRequest('POST','game-revenge.php')
  }
  if (e.target.classList.contains('btn-back'))
    exitConfirm()
})

function exitConfirm () {
  if (confirm('Czy chcesz opuścić grę?')) {
    makeRequest('POST','game-disconnect.php')
    window.location.href = './index.php'
    return true
  }
  return false
}