export function makeRequest(method, endpoint, callback = null, form = null) {
    const errorMessage = document.querySelector('.error-message')
    errorMessage.textContent = ''
    const endpointLocation = 'php/GameEvents/'
    const url = endpointLocation + endpoint

    const xhr = new XMLHttpRequest()
    xhr.open(method, url, true)
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            const response = JSON.parse(xhr.response)
            if (response.success === true) {
                if (callback)
                    callback(response.content)
            } else
                errorMessage.textContent = response.errorMessage

        }
    }
    xhr.send(form ? new FormData(form) : null)
}