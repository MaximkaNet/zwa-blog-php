const renderErrors = (errors) => {
    if(!errors) return

    const errorContainer = document.querySelector(".auth-errors")

    if(!errorContainer) {
        console.error("Error container not found")
        return
    }

    // Clear error container
    errorContainer.innerHTML = ""

    const appendError = (error) => {
        const liError = document.createElement("li")
        liError.classList.add("error")
        const messageSpan = document.createElement("span")
        messageSpan.innerText = error.message
        const errorStatusIcon = document.createElement("img")
        errorStatusIcon.classList.add("error-status-img")
        errorStatusIcon.src = "/assets/images/info-error.svg"
        liError.appendChild(errorStatusIcon)
        liError.appendChild(messageSpan)
        // Add error
        errorContainer.appendChild(liError)
    }

    errors.map(appendError)
}

function isError(code) {
    return (
        code === 400
        || code === 401
        || code === 403
        || code === 404
        || code === 409
    )
}

const handleForm = ({form, url, redirect}) => {
    if(!form) return
    form.onsubmit = async (event) => {
        event.preventDefault()
        const options = {
            method: "POST",
            body: new FormData(form)
        }
        const apiResponse = await fetch(url, options)
        const response = await apiResponse.json()

        if(isError(apiResponse.status)) {
            renderErrors(response.errors)
        }
        else if(apiResponse.status === 200) {
            window.location.href = redirect
        }
    }
}

export { handleForm, renderErrors }